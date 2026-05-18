<?php

namespace App\Jobs;

use DB;
use Mail;
use Exception;
use App\JobProgress;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRetailerImeiStockReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 1;

    protected $filePath;
    protected $jobId;

    public function __construct($filePath, $jobId)
    {
        $this->filePath = $filePath;
        $this->jobId    = $jobId;
    }

    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        try {

            /* ============================
             * 1️⃣ Count total rows
             * ============================ */
            $totalRows = DB::table('sales as t1')
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('smsdetails as s')
                        ->whereColumn('s.sno', 't1.sno');
                })
                ->count();

            JobProgress::where('job_id', $this->jobId)->update([
                'status'     => 'processing',
                'message'    => 'Report generation started.',
                'started_at' => now(),
                'total'      => $totalRows,
                'inserted'   => 0,
                'progress'   => 0,
            ]);

            /* ============================
             * 2️⃣ Prepare export directory
             * ============================ */
            $fullPath  = storage_path('app/' . $this->filePath);
            $directory = dirname($fullPath);

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            /* ============================
             * 3️⃣ Streaming cursor
             * ============================ */
            $counter = 0;

            $rows = DB::table('sales as t1')
                ->select(
                    't2.firstname as distributor',
                    't2.officeid as distributorId',
                    't3.firstname as retailer',
                    't3.officeid as retailerId',
                    't4.name as brand',
                    't5.name as product',
                    't5.model as model',
                    't1.sno',
                    't1.imei'
                )
                ->join('users as t2', 't1.user_id', '=', 't2.id')
                ->join('users as t3', 't1.ruser_id', '=', 't3.id')
                ->leftJoin('brands as t4', 't1.brand_id', '=', 't4.id')
                ->leftJoin('products as t5', 't1.product_id', '=', 't5.id')
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('smsdetails as s')
                        ->whereColumn('s.sno', 't1.sno');
                })
                ->orderBy('t1.id')
                ->cursor();

            /* ============================
             * 4️⃣ Generator with progress
             * ============================ */
            $generator = function () use ($rows, &$counter, $totalRows) {
                foreach ($rows as $row) {
                    $counter++;

                    if ($counter % 1000 === 0 || $counter === $totalRows) {
                        $percentage = $totalRows > 0
                            ? round(($counter / $totalRows) * 100, 2)
                            : 100;

                        JobProgress::where('job_id', $this->jobId)->update([
                            'inserted' => $counter,
                            'progress' => $percentage,
                            'message'  => "Processing... {$percentage}% ({$counter}/{$totalRows})",
                        ]);
                    }

                    yield [
                        '#'                => $counter,
                        'Distributor'      => $row->distributor ?? '-',
                        'Distributor Code' => $row->distributorId ?? '-',
                        'Retailer'         => $row->retailer ?? '-',
                        'Retailer Code'    => $row->retailerId ?? '-',
                        'Brand'            => $row->brand ?? '-',
                        'Product Name'     => $row->product ?? '-',
                        'Product Model'    => $row->model ?? '-',
                        'IMEI 1'           => $row->sno,
                        'IMEI 2'           => $row->imei ?: '-',
                    ];
                }
            };

            /* ============================
             * 5️⃣ Generate Excel (FIXED)
             * ============================ */
            (new FastExcel($generator()))->export($fullPath);

            /* ============================
             * 6️⃣ Mark completed
             * ============================ */
            JobProgress::where('job_id', $this->jobId)->update([
                'status'      => 'completed',
                'progress'    => 100,
                'finished_at' => now(),
                'message'     => "Report generated successfully. Total rows: {$counter}",
            ]);

            // /* ============================
            //  * 7️⃣ Send email
            //  * ============================ */
            // Mail::raw(
            //     'Retailer IMEI Stock Report is ready to download now. Visit DMS and download it.',
            //     function ($message) {
            //         $message->to('kamranhosan05@gmail.com')
            //             ->subject('Retailer IMEI Stock Report Ready');
            //     }
            // );

        } catch (Exception $e) {

            JobProgress::where('job_id', $this->jobId)->update([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
