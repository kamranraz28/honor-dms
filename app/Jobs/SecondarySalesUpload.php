<?php

namespace App\Jobs;

use App\Purchase;
use App\User;
use App\Stock;
use App\Sale;
use App\Smsdetail;
use App\JobProgress;
use App\Helpers\DateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SecondarySalesUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvPath;
    protected $jobId;

    public function __construct($csvPath, $jobId)
    {
        $this->csvPath = $csvPath;
        $this->jobId   = $jobId;
    }

    public function handle()
    {
        $path = Storage::disk('local')->path($this->csvPath);
        $rows = array_map('str_getcsv', file($path));
        $csv = array_slice($rows, 1);
        $total = count($csv);

        JobProgress::where('job_id', $this->jobId)->update([
            'status'     => 'processing',
            'total'      => $total,
            'started_at' => now(),
            'progress'   => 0,
            'message'    => 'Secondary Sales processing started...',
        ]);

        if ($total === 0) {
            JobProgress::where('job_id', $this->jobId)->update([
                'status'      => 'completed',
                'progress'    => 100,
                'finished_at' => now(),
                'message'     => 'CSV empty.'
            ]);
            return;
        }

        /** ---------------------------------------------------------
         * PRELOAD ALL DATA FIRST (massively increases speed)
         * -------------------------------------------------------- */
        $retailerCodes = array_unique(array_column($csv, 0));
        $imeis = array_unique(array_column($csv, 1));

        $users    = User::whereIn('officeid', $retailerCodes)->get()->keyBy('officeid');
        $stocksDB = Stock::whereIn('sno', $imeis)->orWhereIn('imei', $imeis)->get();

        $stocks = [];
        foreach ($stocksDB as $s) {
            $stocks[$s->sno] = $s;
            $stocks[$s->imei] = $s;
        }

        $primary = Purchase::whereIn('sno', $imeis)
                           ->orWhereIn('imei', $imeis)
                           ->get()
                           ->keyBy('sno')
                           ->merge(
                               Purchase::whereIn('imei', $imeis)->get()->keyBy('imei')
                           );

        $existingSales = Sale::whereIn('sno', $imeis)
                             ->orWhereIn('imei', $imeis)
                             ->get();

        $existingMap = [];
        foreach ($existingSales as $sale) {
            $existingMap[$sale->sno] = $sale;
            $existingMap[$sale->imei] = $sale;
        }

        $tertiaryMap = Smsdetail::whereIn('sno', $imeis)
                                ->orWhereIn('imei', $imeis)
                                ->pluck('sno')
                                ->merge(
                                    Smsdetail::whereIn('imei', $imeis)->pluck('imei')
                                )
                                ->toArray();

        /** ---------------------------------------------------------
         * TRACKERS
         * -------------------------------------------------------- */
        $noRetailer   = [];
        $noStock      = [];
        $duplicates   = [];
        $tertiarySold = [];
        $insertBatch  = [];

        $inserted = 0;

        /** ---------------------------------------------------------
         * FAST PROCESSING LOOP
         * -------------------------------------------------------- */
        foreach ($csv as $row) {

            $retailerCode = trim($row[0]);
            $imei         = trim($row[1]);
            $date = isset($row[2]) ? DateHelper::parseCsvDate($row[2]) : now();

            /** 1. Retailer */
            $retailer = $users[$retailerCode] ?? null;
            if (!$retailer) {
                $noRetailer[] = $retailerCode;
                continue;
            }

            /** 2. Stock */
            $stock = $stocks[$imei] ?? null;
            if (!$stock) {
                $noStock[] = $imei;
                continue;
            }

            /** 3. Primary sale */
            $priSale = $primary[$imei] ?? null;

            /** 4. Existing secondary sale */
            if (isset($existingMap[$imei])) {

                $sale = $existingMap[$imei];

                $sale->update([
                    'user_id'    => $priSale->user_id,
                    'ruser_id'   => $retailer->id,
                    'stock_id'   => $stock->id,
                    'product_id' => $stock->product_id,
                    'brand_id'   => $stock->brand_id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                if (empty($sale->memo)) {
                    $sale->update(['memo' => $sale->id . '0']);
                }

                $duplicates[] = ['sno' => $imei];
                continue;
            }

            /** 5. Tertiary sold */
            if (in_array($imei, $tertiaryMap)) {
                $tertiarySold[] = $imei;
                continue;
            }

            /** 6. NEW secondary sale (batch insert) */
            $insertBatch[] = [
                'user_id'    => $priSale->user_id,
                'ruser_id'   => $retailer->id,
                'stock_id'   => $stock->id,
                'product_id' => $stock->product_id,
                'brand_id'   => $stock->brand_id,
                'sno'        => $stock->sno,
                'imei'       => $stock->imei,
                'created_at' => $date,
                'updated_at' => $date
            ];
        }

        /** ---------------------------------------------------------
         * MASS INSERT + MEMO UPDATE
         * -------------------------------------------------------- */
        if (!empty($insertBatch)) {

            DB::transaction(function () use (&$insertBatch, &$inserted) {

                Sale::insert($insertBatch);

                $count = count($insertBatch);
                $inserted = $count;

                $lastId  = Sale::max('id');
                $firstId = $lastId - $count + 1;

                Sale::whereBetween('id', [$firstId, $lastId])
                    ->update(['memo' => DB::raw("CONCAT(id,'0')")]);
            });
        }

        /** ---------------------------------------------------------
         * FINAL PROGRESS UPDATE (only once!)
         * -------------------------------------------------------- */
        JobProgress::where('job_id', $this->jobId)->update([
            'status'             => 'completed',
            'progress'           => 100,
            'finished_at'        => now(),
            'inserted'           => $inserted,
            'duplicates'         => count($duplicates),
            'log_details'        => json_encode($duplicates),
            'no_stock'           => json_encode($noStock),
            'no_dealer'          => json_encode($noRetailer),
            'tertiary_sold_list' => json_encode($tertiarySold),
            'message'            => "Secondary sales completed."
        ]);
    }
}
