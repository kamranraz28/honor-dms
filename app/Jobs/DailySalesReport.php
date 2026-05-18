<?php

namespace App\Jobs;

use DB;
use App\JobProgress;
use App\Purchase;
use App\Sale;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;
    public $tries = 1;

    protected $filePath;
    protected $jobId;
    protected $fdate;
    protected $todate;
    protected $type;
    protected $distributor_id;

    public function __construct($filePath, $jobId, $fdate, $todate, $type, $distributor_id)
    {
        $this->filePath = $filePath;
        $this->jobId = $jobId;
        $this->fdate = $fdate;
        $this->todate = $todate;
        $this->type = $type;
        $this->distributor_id = $distributor_id;
    }

    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        /* ============================
         * 1️⃣ Build Query (OPTIMIZED)
         * ============================ */
        if ($this->type === 'Purchase') {

            $query = Purchase::with([
                'product:id,name,model,color',
                'user:id,firstname,officeid',
                'district:id,name',
                'upazila:id,name',
                'brand:id,name',
                'order.orderposting.orderspostingDetails' // ✅ FIXED
            ])->whereBetween('created_at', [
                        $this->fdate . ' 00:00:00',
                        $this->todate . ' 23:59:59'
                    ]);


            JobProgress::where('job_id', $this->jobId)->update([
                'message' => 'Primary Sales Report From ' . $this->fdate . ' To ' . $this->todate,
            ]);

        } else {

            $query = Sale::with([
                'product:id,name,model',
                'user:id,firstname,officeid',
                'sr:id,name,officeid',
                'retailer:id,name,officeid',
                'district:id,name',
                'upazila:id,name',
                'brand:id,name'
            ])->whereBetween('created_at', [
                        $this->fdate . ' 00:00:00',
                        $this->todate . ' 23:59:59'
                    ]);

            JobProgress::where('job_id', $this->jobId)->update([
                'message' => 'Secondary Sales Report From ' . $this->fdate . ' To ' . $this->todate,
            ]);
        }

        if ($this->distributor_id) {
            $query->where('user_id', $this->distributor_id);
        }

        $totalRows = $query->count();

        /* ============================
         * 2️⃣ Init Progress
         * ============================ */
        JobProgress::where('job_id', $this->jobId)->update([
            'status' => 'processing',
            'started_at' => now(),
            'total' => $totalRows,
            'inserted' => 0,
            'progress' => 0,
        ]);

        /* ============================
         * 3️⃣ Prepare File Path
         * ============================ */
        $fullPath = storage_path('app/' . $this->filePath);
        $directory = dirname($fullPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        /* ============================
         * 4️⃣ Generator + chunk()
         * ============================ */
        $counter = 0;

        $generator = function () use ($query, &$counter, $totalRows) {

            foreach ($query->cursor() as $row) {
                $counter++;

                if ($this->type === 'Purchase') {
                    yield [
                        '#' => $counter,
                        'Order Number' => $row->order_number,
                        'Status' => $row->status ? 'Received' : 'Not Received',
                        'Distributor' => $row->user->firstname ?? '',
                        'Distributor ID' => $row->user->officeid ?? '',
                        'District' => $row->district->name ?? '',
                        'Upazila' => $row->upazila->name ?? '',
                        'Brand' => $row->brand->name ?? '',
                        'Product Name' => $row->product->name ?? '',
                        'Product Model' => $row->product->model ?? '',
                        'Color' => $row->product->color ?? '',
                        'IMEI 1' => $row->sno ?? '',
                        'IMEI 2' => $row->imei ?? '',
                        'Price' => (function () use ($row) {

                            // Normalize into a flat collection (never null)
                            $details = collect(optional(optional($row->orderposting)->OrderspostingDetails))
                                ->flatten(1);

                            // Find matching product detail
                            $detail = $details->first(function ($d) use ($row) {
                                return isset($d->product_id) && $d->product_id == $row->product_id;
                            });

                            return $detail->price ?? '-';

                        })(),


                        'Created Date' => $row->created_at->format('d-M-Y'),
                    ];
                } else {
                    yield [
                        '#' => $counter,
                        'Memo' => $row->memo,
                        'Distributor' => $row->user->firstname ?? '',
                        'Distributor ID' => $row->user->officeid ?? '',
                        'SR Name' => $row->sr->name ?? '',
                        'SR ID' => $row->sr->officeid ?? '',
                        'Retailer' => $row->retail->firstname ?? '',
                        'Retailer ID' => $row->retail->officeid ?? '',
                        'District' => $row->district->name ?? '',
                        'Upazila' => $row->upazila->name ?? '',
                        'Brand' => $row->brand->name ?? '',
                        'Product Name' => $row->product->name ?? '',
                        'Product Model' => $row->product->model ?? '',
                        'IMEI 1' => $row->sno ?? '',
                        'IMEI 2' => $row->imei ?? '',
                        'Created Date' => $row->created_at->format('d-M-Y H:i:s'),
                    ];
                }

                // ✅ Progress update every 1000 rows
                if ($counter % 1000 === 0 || $counter === $totalRows) {
                    $percentage = $totalRows > 0
                        ? round(($counter / $totalRows) * 100, 2)
                        : 100;

                    JobProgress::where('job_id', $this->jobId)->update([
                        'inserted' => $counter,
                        'progress' => $percentage,
                    ]);
                }
            }
        };


        /* ============================
         * 5️⃣ Export Excel
         * ============================ */
        (new FastExcel($generator()))->export($fullPath);

        /* ============================
         * 6️⃣ Mark Completed
         * ============================ */
        JobProgress::where('job_id', $this->jobId)->update([
            'status' => 'completed',
            'progress' => 100,
            'inserted' => $counter,
            'finished_at' => now(),
        ]);
    }
}
