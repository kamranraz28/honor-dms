<?php

namespace App\Jobs;

use App\Sale;
use App\Smsdetail;
use App\User;
use DateTime;
use App\Stock;
use App\Purchase;
use App\Models\Orderspostingdetailsimi;
use App\JobProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PrimarySalesBulkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvPath;
    protected $jobId;

    public $tries = 1;
    public $timeout = 0;
    public $maxExceptions = 1;

    public function __construct($csvPath, $jobId)
    {
        $this->csvPath = $csvPath;
        $this->jobId   = $jobId;
    }

    public function handle()
    {
        // Logging setup
        $logChannel = 'primary_sales_bulk_upload';
        $logPath    = storage_path('logs/primary_sales_bulk_upload.log');
        $logger     = new \Monolog\Logger($logChannel);
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::INFO));

        // Load CSV
        $path = Storage::disk('local')->path($this->csvPath);
        $rows = array_map('str_getcsv', file($path));
        $csv_data = array_slice($rows, 1); // skip header
        $total    = count($csv_data);

        $logger->info("[JobID: {$this->jobId}] CSV loaded. Total rows (excluding header): {$total}");

        // Helper for date formatting
        $formatDate = function ($dateStr) {
            $dateStr = trim($dateStr);
            if ($dateStr === '') {
                return date('Y-m-d H:i:s');
            }
            $dt = DateTime::createFromFormat('n/j/Y h:i:s A', $dateStr);
            if ($dt !== false) {
                return $dt->format('Y-m-d H:i:s');
            }
            $timestamp = strtotime($dateStr);
            return $timestamp !== false ? date('Y-m-d H:i:s', $timestamp) : date('Y-m-d H:i:s');
        };

        // Initialize JobProgress
        JobProgress::where('job_id', $this->jobId)->update([
            'status'     => 'processing',
            'total'      => $total,
            'started_at' => now(),
            'progress'   => 0,
            'inserted'   => 0,
            'duplicates' => 0,
            'remaining'  => $total,
            'message'    => 'Job started. Processing records...',
        ]);

        if ($total === 0) {
            JobProgress::where('job_id', $this->jobId)->update([
                'status'      => 'completed',
                'progress'    => 100,
                'finished_at' => now(),
                'message'     => 'No records found in CSV. Nothing to process.',
            ]);
            return;
        }

        $chunkSize   = 500;
        $insertData  = [];
        $counter     = 0;
        $inserted    = 0;
        $duplicates  = 0;
        $soldList = [];
        $tertiarySoldList = []; // <-- Add this to collect SNOs with tertiary sold
        $duplicateList = [];
        $noStockList = []; // <-- Add this to collect SNOs with no stock
        $noDealerList = []; // <-- Add this to collect distributorIds with no user

        // Preload users
        $distributorIds = array_unique(array_column($csv_data, 0));
        $users          = User::whereIn('officeid', $distributorIds)->get()->keyBy('officeid');

        // Preload stocks and key by both SNO and IMEI
        $snos   = array_map(function($row) { return trim($row[1]); }, $csv_data);
        $stocksRaw = Stock::whereIn('sno', $snos)
            ->orWhereIn('imei', $snos)
            ->get();

        $stocks = [];
        foreach ($stocksRaw as $stockItem) {
            if ($stockItem->sno) {
                $stocks[$stockItem->sno] = $stockItem;
            }
            if ($stockItem->imei) {
                $stocks[$stockItem->imei] = $stockItem;
            }
        }

        foreach ($csv_data as $row) {
            $distributor = trim($row[0]);
            $sno         = trim($row[1]);
            $date = isset($row[2]) ? $formatDate($row[2]) : date('Y-m-d H:i:s');

            $user  = isset($users[$distributor]) ? $users[$distributor] : null;
            $stock = isset($stocks[$sno]) ? $stocks[$sno] : null;

            // If user not found, collect distributorId and continue
            if (!$user) {
                if (!in_array($distributor, $noDealerList)) {
                    $noDealerList[] = $distributor;
                    JobProgress::where('job_id', $this->jobId)->update([
                        'no_dealer' => json_encode($noDealerList),
                    ]);
                }
                continue;
            }

            // If stock not found, collect SNO and continue
            if (!$stock) {
                $noStockList[] = $sno;
                JobProgress::where('job_id', $this->jobId)->update([
                    'no_stock' => json_encode($noStockList),
                ]);
                continue;
            }

            // Only check for duplicates in Purchase table and store SNOs in log_details
            $existsPurchase = Purchase::where('sno', $sno)->orWhere('imei', $sno)->exists();
            if ($existsPurchase) {
                $duplicates++;
                $duplicateList[] = ['sno' => $sno];
                JobProgress::where('job_id', $this->jobId)->update([
                    'duplicates' => $duplicates,
                    'log_details' => json_encode($duplicateList),
                ]);
                continue;
            }

            // Check for already sold (Sale table) and store SNO in sold_list as JSON array of SNOs
            $existsSale = Sale::where('sno', $sno)->orWhere('imei', $sno)->exists();
            if ($existsSale) {
                if (!in_array($sno, $soldList)) {
                    $soldList[] = $sno;
                }
                JobProgress::where('job_id', $this->jobId)->update([
                    'sold_list' => json_encode($soldList),
                ]);
                continue;
            }

            // Check for already sold (Smsdetail table) and store SNO in tertiary_sold_list as JSON array of SNOs
            $existsTertiary = Smsdetail::where('sno', $sno)->orWhere('imei', $sno)->exists();
            if ($existsTertiary) {
                if (!in_array($sno, $tertiarySoldList)) {
                    $tertiarySoldList[] = $sno;
                }
                JobProgress::where('job_id', $this->jobId)->update([
                    'tertiary_sold_list' => json_encode($tertiarySoldList),
                ]);
                continue;
            }

            $orderNumber = Orderspostingdetailsimi::where('imi', $sno)->value('order_number');

            $insertData[] = [
                'stock_id'     => $stock->id,
                'user_id'      => $user->id,
                'order_number' => $orderNumber,
                'dis_id'       => $user->district_id,
                'up_id'        => $user->upazila_id,
                'product_id'   => $stock->product_id,
                'brand_id'     => $stock->brand_id,
                'imei'         => $stock->imei ?: null,
                'sno'          => $stock->sno,
                'created_at'   => $date,
                'updated_at'   => $date,
            ];

            $counter++;

            // Insert in chunks
            if ($counter % $chunkSize === 0) {
                Purchase::insert($insertData);
                $stockIds = array_column($insertData, 'stock_id');
                Stock::whereIn('id', $stockIds)->update(['details' => 'sold']);
                $inserted += count($insertData);
                $percent   = $total > 0 ? round(($inserted / $total) * 100, 2) : 100;

                JobProgress::where('job_id', $this->jobId)->update([
                    'inserted'  => $inserted,
                    'progress'  => $percent,
                    'remaining' => $total - ($inserted + $duplicates),
                    'message'   => "Inserted {$inserted} of {$total} rows. Progress: {$percent}%",
                    'no_stock'  => json_encode($noStockList),
                    'sold_list' => json_encode($soldList),
                    'tertiary_sold_list' => json_encode($tertiarySoldList),
                ]);
                $insertData = [];
            }
        }

        // Insert remaining
        if (!empty($insertData)) {
            Purchase::insert($insertData);
            $stockIds = array_column($insertData, 'stock_id');
            Stock::whereIn('id', $stockIds)->update(['details' => 'sold']);
            $inserted += count($insertData);
        }

        $percent = $total > 0 ? round(($inserted / $total) * 100, 2) : 100;

        // Final job update
        JobProgress::where('job_id', $this->jobId)->update([
            'inserted'   => $inserted,
            'duplicates' => $duplicates,
            'progress'   => 100,
            'remaining'  => 0,
            'status'     => 'completed',
            'finished_at'=> now(),
            'message'    => "Job completed successfully.",
            'log_details'=> json_encode($duplicateList),
            'no_stock'   => json_encode($noStockList),
            'sold_list'  => json_encode($soldList),
            'tertiary_sold_list' => json_encode($tertiarySoldList),
        ]);

        $logger->info("[JobID: {$this->jobId}] Completed. Inserted {$inserted}, skipped {$duplicates}");
    }
}
