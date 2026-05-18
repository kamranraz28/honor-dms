<?php

namespace App\Repositories;

use App\Exceptions\DomainException;
use App\Smsdetail;
use Carbon\Carbon;

class TertiarySaleRepository
{
    public function getAllTertiaries()
    {
        return Smsdetail::with('user','product','brand')
            ->orderByDesc('created_at')
            ->get();
    }
    public function getTertiaryByDateRange($fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Smsdetail::with('user','product','brand')
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function getTertiaryByDateAndRetailerId($retailerId,$fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Smsdetail::with('user','product','brand')
            ->where('user_id',$retailerId)
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function findByIMEI(string $imei): Smsdetail
    {
        $terSale = $this->queryByIMEI($imei)->first();

        if (!$terSale) {
            throw new DomainException(
                "Tertiary Sale with IMEI '{$imei}' not found in the system."
            );
        }

        return $terSale;
    }

    public function getSalesByIMEIs(array $imeis)
    {
        return Smsdetail::whereIn('sno', $imeis)
            ->orWhereIn('imei', $imeis)
            ->get();
    }


    public function findByDateRange(string $from, string $to)
    {
        $start = Carbon::parse($from)->startOfDay();
        $end   = Carbon::parse($to)->endOfDay();
        return Smsdetail::whereBetween('created_at', [$start, $end]);
    }

    public function ensureNotAvailableByIMEI(string $imei): void
    {
        if ($this->queryByIMEI($imei)->exists()) {
            throw new DomainException(
                "Tertiary Sale with IMEI '{$imei}' already exists in the system."
            );
        }
    }

    public function create(array $data): Smsdetail
    {
        return Smsdetail::create($data);
    }

    public function destroy(string $imei)
    {
        return $this->findByIMEI($imei)->delete();
    }

    public function insertMany(array $data)
    {
        return Smsdetail::insert($data);
    }


    /* =======================
       Internal helper method
       ======================= */

    private function queryByIMEI(string $imei)
    {
        return Smsdetail::where('sno', $imei)
            ->orWhere('imei', $imei);
    }

    public function findByIMEIforCycle(string $imei)
    {
        return $this->queryByIMEI($imei)->first();
    }
}
