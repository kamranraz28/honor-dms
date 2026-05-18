<?php

namespace App\Jobs;

use App\JobProgress;
use App\Models\Orderspostingdetailsimi;
use App\Purchase;
use App\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeliveryConfirmation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 0;
    public $maxExceptions = 1;

    protected $jobId;
    protected $orderModel;

    public function __construct($jobId, $orderModel)
    {
        $this->jobId = $jobId;
        $this->orderModel = $orderModel;
    }

    public function handle()
{
    try {
        $orderNumber = $this->orderModel->orader_number;

        $total = Orderspostingdetailsimi::where('order_number', $orderNumber)->count();

        $jobProgress = JobProgress::updateOrCreate(
            ['job_id' => $this->jobId],
            [
                'status' => 'processing',
                'started_at' => now(),
                'total' => $total,
                'progress' => 0,
                'inserted' => 0,
                'message' => 'Job started...',
            ]
        );

        // Cache distributor info ONCE
        $user = $this->orderModel->Order->usersd ?? null;

        $chunkSize = 1000; // BIG WIN
        $processed = 0;
        $inserted = 0;

        Orderspostingdetailsimi::where('order_number', $orderNumber)
            ->select('id', 'imi', 'imi2', 'order_number')
            ->chunkById($chunkSize, function ($rows) use (
                &$processed,
                &$inserted,
                $total,
                $jobProgress,
                $user
            ) {

                // Collect all serials
                $serials = [];
                foreach ($rows as $row) {
                    $serials[] = $row->imi ?: $row->imi2;
                }

                $stocksRaw = Stock::whereIn('sno', $serials)
                    ->orWhereIn('imei', $serials)
                    ->get(['id', 'brand_id', 'product_id', 'sno', 'imei']);

                $stocks = [];

                foreach ($stocksRaw as $item) {
                    if ($item->sno) {
                        $stocks[$item->sno] = $item;
                    }
                    if ($item->imei) {
                        $stocks[$item->imei] = $item;
                    }
                }


                $toInsert = [];

                foreach ($rows as $row) {
                    $sno = $row->imi ?: $row->imi2;

                    if (!isset($stocks[$sno])) {
                        continue;
                    }

                    $stock = $stocks[$sno];

                    $toInsert[] = [
                        'user_id' => $user->id ?? null,
                        'order_number' => $row->order_number,
                        'dis_id' => $user->district_id ?? null,
                        'up_id' => $user->upazila_id ?? null,
                        'stock_id' => $stock->id,
                        'product_id' => $stock->product_id,
                        'brand_id' => $stock->brand_id,
                        'quantity' => 1,
                        'sno' => $stock->sno,
                        'imei' => $stock->imei,
                        'status' => 0,
                        'from_app' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if ($toInsert) {
                    DB::table('purchases')->insert($toInsert);
                    $inserted += count($toInsert);
                }

                $processed += count($rows);

                // Update progress every ~5%
                $percent = (int)(($processed / max(1, $total)) * 100);
                if ($percent % 5 === 0) {
                    $jobProgress->update([
                        'inserted' => $inserted,
                        'progress' => $percent,
                    ]);
                }
            });

        $jobProgress->update([
            'status' => 'completed',
            'finished_at' => now(),
            'progress' => 100,
            'message' => 'Delivery confirmation completed.',
        ]);
    } catch (Throwable $e) {
        JobProgress::where('job_id', $this->jobId)->update([
            'status' => 'failed',
            'message' => $e->getMessage(),
        ]);

        throw $e;
    }
}
}
