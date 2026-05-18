<?php

namespace App\Services;

use Rap2hpoutre\FastExcel\FastExcel;

class ExcelDownloadService
{
    /**
     * Download Excel file using FastExcel
     *
     * @param string $baseName   // name without date or extension
     * @param array|\Illuminate\Support\Collection $data
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function download(string $baseName, $data)
    {
        // Generate filename with timestamp
        $fileName = $baseName . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return (new FastExcel($data))->download($fileName);
    }
}
