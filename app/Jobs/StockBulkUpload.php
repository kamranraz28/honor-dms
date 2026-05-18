<?php

namespace App\Jobs;

use App\JobProgress;
use App\Product;
use App\Stock;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StockBulkUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvPath;
    public $jobId; // Unique job identifier

    // Prevent automatic retry
    public $tries = 1;      // Only attempt once
    public $timeout = 0;    // No time limit
    public $maxExceptions = 1; // Stop after first exception

    public function __construct($csvPath, $jobId)
    {
        $this->csvPath = $csvPath;
        $this->jobId = $jobId;
    }

    public function handle()
    {
        // Logging setup
        $logChannel = 'stock_bulk_upload';
        $logPath = storage_path('logs/stock_bulk_upload.log');
        $logger = new \Monolog\Logger($logChannel);
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::INFO));

        $path = Storage::disk('local')->path($this->csvPath);
        $rows = array_map('str_getcsv', file($path));
        $csv_data = array_slice($rows, 1); // skip header
        $total = count($csv_data);

        $logger->info("[JobID: {$this->jobId}] CSV loaded. Total rows (excluding header): " . $total);

        // Set initial JobProgress
        JobProgress::where('job_id', $this->jobId)->update([
            'status' => 'processing',
            'total' => $total,
            'started_at' => now(),
            'progress' => 0,
            'inserted' => 0,
            'duplicates' => 0,
            'remaining' => $total,
            'message' => 'Job is processing.',
        ]);

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

        // 1. Gather all SNOs and IMEIs from CSV
        $csvSnos = [];
        $csvImeis = [];
        foreach ($csv_data as $row) {
            $csvSnos[] = trim($row[2]);
            $csvImeis[] = trim($row[3]);
        }

        // 2. Fetch all existing SNOs and IMEIs from DB in one query
        $existingSnos = Stock::whereIn('sno', $csvSnos)->pluck('sno')->toArray();
        $existingSnos = array_flip($existingSnos);

        // 3. Filter out duplicates in PHP
        $before = count($csv_data);
        $filtered_data = [];
        $duplicates = 0;
        $duplicateList = []; // <-- Collect duplicate IMEI/SNO here
        foreach ($csv_data as $row) {
            $sno = trim($row[2]);
            if (isset($existingSnos[$sno])) {
                $duplicates++;
                $duplicateList[] = [
                    'sno' => $sno,
                ];
                $logger->warning("[JobID: {$this->jobId}] Duplicate found (SNO: {$sno}). Skipping this entry.");
                // Update duplicates count and list in JobProgress
                JobProgress::where('job_id', $this->jobId)->update([
                    'duplicates' => $duplicates,
                    'message' => "Found {$duplicates} duplicates so far.",
                    'log_details' => json_encode($duplicateList), // <-- Store as JSON
                ]);
                continue;
            }
            $filtered_data[] = $row;
        }
        $csv_data = $filtered_data;
        $logger->info("[JobID: {$this->jobId}] After duplicate SNO/IMEI check: " . count($csv_data) . " rows remain (removed " . ($before - count($csv_data)) . ")");

        // Update after duplicate check
        JobProgress::where('job_id', $this->jobId)->update([
            'duplicates' => $duplicates,
            'remaining' => count($csv_data),
            'message' => "After duplicate check: {$duplicates} duplicates found.",
            'log_details' => json_encode($duplicateList), // <-- Store as JSON
        ]);

        // Prepare models for lookup
        $models = [];
        foreach ($csv_data as $row) {
            $productName = trim($row[0]);
            $color = trim($row[1]);
            $model = $productName . '_' . $color;
            $models[] = $model;
        }
        $products = Product::whereIn('model', $models)->get()->keyBy('model');

        // Prepare stock data in chunks
        $stockData = [];
        $chunkSize = 500;
        $counter = 0;
        $inserted = 0;
        $total = count($csv_data);
        $modelErrors = []; // <-- Collect model errors here

        foreach ($csv_data as $row) {
            $productName = trim($row[0]);
            $color = trim($row[1]);
            $model = $productName . '_' . $color;
            $sno = trim($row[2]);
            $imei = trim($row[3]);
            $wperiod = trim($row[4]);
            $date = isset($row[5]) ? $formatDate($row[5]) : date('Y-m-d H:i:s');

            if (!isset($products[$model])) {
                $logger->warning("[JobID: {$this->jobId}] Model not found for row: " . implode(',', $row));
                // Store model error
                $modelErrors[] = [
                    'model' => "{$productName} {$color}",
                    'row' => $row
                ];
                // Optionally update JobProgress with model_error after each error (or do it once after loop)
                JobProgress::where('job_id', $this->jobId)->update([
                    'model_error' => json_encode(array_column($modelErrors, 'model'))
                ]);
                continue;
            }

            $product = $products[$model];

            $stockData[] = [
                'imei'       => $imei,
                'sno'        => $sno,
                'product_id' => $product->id,
                'brand_id'   => $product->brand_id,
                'wperiod'    => $wperiod,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $counter++;

            if ($counter % $chunkSize === 0) {
                Stock::insert($stockData);
                $inserted += count($stockData);
                $percent = $total > 0 ? round(($inserted / $total) * 100, 2) : 100;
                // $logger->info("[JobID: {$this->jobId}] Inserted {$inserted} / {$total} rows ({$percent}%)");
                // $logger->info("[JobID: {$this->jobId}] Last chunk details: " . json_encode($stockData));
                $stockData = [];

                // Update JobProgress after each chunk
                JobProgress::where('job_id', $this->jobId)->update([
                    'inserted' => $inserted,
                    'progress' => $percent,
                    'remaining' => $total - $inserted,
                    'message' => "Inserted {$inserted} of {$total} rows. Progress: {$percent}%",
                ]);
            }
        }

        // Insert remaining records
        if (!empty($stockData)) {
            Stock::insert($stockData);
            $inserted += count($stockData);
            $percent = $total > 0 ? round(($inserted / $total) * 100, 2) : 100;
            // $logger->info("[JobID: {$this->jobId}] Inserted all {$inserted} rows. CSV import completed. (100%)");
            // $logger->info("[JobID: {$this->jobId}] Final chunk details: " . json_encode($stockData));

            // Final update
            JobProgress::where('job_id', $this->jobId)->update([
                'inserted' => $inserted,
                'progress' => 100,
                'remaining' => 0,
                'status' => 'completed',
                'finished_at' => now(),
                'message' => 'Job completed successfully.',
            ]);
        } else {
            // If nothing to insert, still mark as completed
            JobProgress::where('job_id', $this->jobId)->update([
                'progress' => 100,
                'status' => 'completed',
                'finished_at' => now(),
                'message' => 'Job completed (no new records to insert).',
            ]);
        }
    }
}
