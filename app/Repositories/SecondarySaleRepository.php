<?php

namespace App\Repositories;

use App\Sale;
use Illuminate\Support\Facades\DB;

class SecondarySaleRepository
{
    public function getAllSecondaries()
    {
        return Sale::with('user','product','retailer')
            ->orderByDesc('created_at')
            ->get();
    }
    public function getSecondaryByDateRange($fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Sale::with('user','product','retailer')
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function getSecondaryByDateAndDistributorId($distributorId,$fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Sale::with('user','product','retailer')
            ->where('user_id',$distributorId)
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function findByIMEI(string $imei): Sale
    {
        $secSale = $this->queryByIMEI($imei)->first();

        if (!$secSale) {
            throw new \Exception(
                "Secondary Sale not found for IMEI '{$imei}'."
            );
        }

        return $secSale;
    }

    public function findByIMEIAndRetailer(int $retailerId, string $imei): Sale
    {
        $secSale = $this->queryByImeiAndRetailer($retailerId,$imei)->first();

        if (!$secSale) {
            throw new \Exception(
                "Secondary Sale not found for IMEI '{$imei}'."
            );
        }

        return $secSale;
    }

    public function ensureNotAvailableByIMEI(string $imei): void
    {
        if ($this->queryByIMEI($imei)->exists()) {
            throw new \Exception(
                "IMEI '{$imei}' already exists in Secondary Sales."
            );
        }
    }

    public function destroy(int $retailerId, string $imei)
    {
        return $this->findByIMEIAndRetailer($retailerId,$imei)->delete();
    }

    /* =======================
       Internal helper method
       ======================= */

    private function queryByIMEI(string $imei)
    {
        return Sale::where('sno', $imei)
            ->orWhere('imei', $imei);
    }
    private function queryByImeiAndRetailer(int $retailerId, string $imei)
    {
        return Sale::where('sno', $imei)
            ->orWhere('imei', $imei)
            ->where('ruser_id',$retailerId);
    }

    public function getStockOut(array $distributorIds, $fdate, $todate)
    {
        return Sale::select(
                'user_id',
                'product_id',
                DB::raw('COUNT(product_id) as stockout')
            )
            ->whereIn('user_id', $distributorIds)
            ->whereBetween('created_at', [$fdate, $todate])
            ->groupBy('user_id', 'product_id')
            ->get()
            ->groupBy(['user_id', 'product_id']);
    }

    public function findByIMEIforCycle(string $imei)
    {
        $secSale = $this->queryByIMEI($imei)->first();
        return $secSale;
    }

}
