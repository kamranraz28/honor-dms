<?php

namespace App\Jobs;

use App\JobProgress;
use App\Order;
use App\Sale;
use App\Smsdetail;
use App\Stock;
use App\Models\Orderspostingdetailsimi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImeiBulkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvPath;
    protected $userId;
    protected $jobId;

    public $tries = 1;          // Only try once
    public $timeout = 0;        // No time limit
    public $maxExceptions = 1;  // Stop after first exception

    public function __construct($csvPath, $userId, $jobId)
    {
        $this->csvPath = $csvPath;
        $this->userId = $userId;
        $this->jobId = $jobId;
    }

    public function handle()
{
    $path = Storage::disk('local')->path($this->csvPath);
    
    $rows = array_map('str_getcsv', file($path));
    $dataRows = array_slice($rows, 1); // Skip header
    $total = count($dataRows);

    if ($total === 0) {
        Log::warning("CSV file is empty: {$this->csvPath}");
        return;
    }

    Log::info("Processing IMEI bulk upload job {$this->jobId} with {$total} rows.");

    JobProgress::where('job_id', $this->jobId)->update([
        'status'     => 'processing',
        'total'      => $total,
        'started_at' => now(),
        'message'    => 'Job started. Processing records...',
    ]);

    // --- Collect all SNOS upfront ---
    $allSnos = array_map(function ($row) {
        return trim($row[4]);
    }, $dataRows);

    // --- Preload duplicates, sales, tertiary in ONE shot ---
    $existingPurchases = Orderspostingdetailsimi::whereIn('imi', $allSnos)
        ->orWhereIn('imi2', $allSnos)
        ->pluck('imi')
        ->merge(Orderspostingdetailsimi::whereIn('imi2', $allSnos)->pluck('imi2'))
        ->toArray();

    $existingSales = Sale::whereIn('sno', $allSnos)
        ->orWhereIn('imei', $allSnos)
        ->pluck('sno')
        ->merge(Sale::whereIn('imei', $allSnos)->pluck('imei'))
        ->toArray();

    $existingTertiary = Smsdetail::whereIn('sno', $allSnos)
        ->orWhereIn('imei', $allSnos)
        ->pluck('sno')
        ->merge(Smsdetail::whereIn('imei', $allSnos)->pluck('imei'))
        ->toArray();

    // --- Preload all relevant stocks in one query ---
    $stocksRaw = Stock::whereIn('sno', $allSnos)
        ->orWhereIn('imei', $allSnos)
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

    // --- Order and details mapping ---
    $firstRow = $dataRows[0];
    $orderId = $firstRow[5];

    $order = Order::with([
        'orderposting.orderspostingdetails.orderspostingdetailsimis'
    ])->find($orderId);

    $orderPosting = $order->orderposting;
    $detailsList = $orderPosting->orderspostingdetails;
    $detailsMap = $detailsList->keyBy('product_id');

    // --- Tracking ---
    $duplicates = [];
    $noStockList = [];
    $soldList = [];
    $tertiarySoldList = [];
    $insertData = [];
    $uploadCounter = [];

    $counter = 0;
    $inserted = 0;
    $chunkSize = 200; // smaller chunks = smoother progress

    foreach ($dataRows as $row) {
        $sno = trim($row[4]);

        // Skip if stock not found
        if (!isset($stocks[$sno])) {
            $noStockList[] = $sno;
            continue;
        }

        // Skip if already exists in purchases
        if (in_array($sno, $existingPurchases)) {
            $duplicates[] = $sno;
            continue;
        }

        // Skip if sold
        if (in_array($sno, $existingSales)) {
            $soldList[] = $sno;
            continue;
        }

        // Skip if tertiary sold
        if (in_array($sno, $existingTertiary)) {
            $tertiarySoldList[] = $sno;
            continue;
        }

        $stock = $stocks[$sno];
        $productId = $stock->product_id;

        if (!isset($detailsMap[$productId])) {
            Log::warning("No order posting detail for product: {$productId}");
            continue;
        }

        $detail = $detailsMap[$productId];
        $uploadedCount = isset($uploadCounter[$productId]) ? $uploadCounter[$productId] : $detail->orderspostingdetailsimis->count();
        $uploadCounter[$productId] = $uploadedCount + 1;

        $insertData[] = [
            'orderspostingdetails_id' => $detail->id,
            'order_number' => $orderId,
            'product_id' => $productId,
            'imi' => $sno,
            'imi2' => $stock->imei,
            'created_by' => $this->userId,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $counter++;
        $inserted++;

        // Insert + update progress every chunk
        if ($counter % $chunkSize === 0) {
            Orderspostingdetailsimi::insert($insertData);
            $insertData = [];

            $percent = $total > 0 ? round(($inserted / $total) * 100, 2) : 100;
            JobProgress::where('job_id', $this->jobId)->update([
                'inserted'   => $inserted,
                'progress'   => $percent,
                'remaining'  => $total - $inserted,
                'message'    => "Inserted {$inserted} of {$total} rows. Progress: {$percent}%",
                'no_stock'   => json_encode($noStockList),
                'log_details'=> json_encode($duplicates),
                'sold_list'  => json_encode($soldList),
                'tertiary_sold_list' => json_encode($tertiarySoldList),
            ]);
        }
    }

    // Insert leftovers
    if (!empty($insertData)) {
        Orderspostingdetailsimi::insert($insertData);
    }

    $totalQuantity = $detailsList->sum('quantity');
    $uploadedTotal = Orderspostingdetailsimi::whereIn('orderspostingdetails_id', $detailsList->pluck('id'))->count();
    $newStatus = $uploadedTotal >= $totalQuantity ? 3 : 2;

    $order->update(['status' => $newStatus]);
    $orderPosting->update(['status' => $newStatus]);

    // Final update
    JobProgress::where('job_id', $this->jobId)->update([
        'inserted'   => $inserted,
        'progress'   => 100,
        'remaining'  => 0,
        'status'     => 'completed',
        'finished_at'=> now(),
        'message'    => "Job completed. Inserted {$inserted}, skipped " . count($duplicates) . ".",
        'no_stock'   => json_encode($noStockList),
        'log_details'=> json_encode($duplicates),
        'sold_list'  => json_encode($soldList),
        'tertiary_sold_list' => json_encode($tertiarySoldList),
    ]);

    Log::info("IMEI bulk upload completed for order {$orderId}: {$counter} rows processed.");
}

}



