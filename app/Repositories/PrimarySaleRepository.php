<?php

namespace App\Repositories;

use App\Purchase;
use Illuminate\Support\Facades\DB;

class PrimarySaleRepository
{
    public function getAllPrimaries()
    {
        return Purchase::with('user','product','orderposting')
            ->orderByDesc('created_at')
            ->get();
    }
    public function getPrimaryByDateRange($fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Purchase::with('user','product','orderposting')
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function getPrimaryByDateAndDistributorId($distributorId,$fromDate, $toDate)
    {
        $start = $fromDate . ' 00:00:00';
        $end   = $toDate . ' 23:59:59';
        return Purchase::with('user','product','orderposting')
            ->where('user_id',$distributorId)
            ->whereBetween('created_at', [$start, $end])
            ->get();
    }
    public function findByIMEI(string $imei): ?Purchase
    {
        return $this->queryByIMEI($imei)->first();
    }
    public function findByBothIMEINullable(string $imei1, string $imei2): ?Purchase
    {
        return $this->queryByBothImei($imei1, $imei2)->first();
    }


    public function findByOrder(int $order)
    {
        return $this->queryByOrder($order)->first();
    }

    public function findByDistributorAndIMEI(int $distributorId, string $imei): Purchase
    {
        return $this->findByIMEIAndDistributor($imei, $distributorId);
    }

    public function ensureNotAvailableByIMEI(string $imei): void
    {
        if ($this->queryByIMEI($imei)->exists()) {
            throw new \Exception(
                "Primary Sale with IMEI: '{$imei}' already exists in the system."
            );
        }
    }

    public function destroy(string $imei): bool
    {
        return $this->findByIMEI($imei)->delete();
    }

    /* =======================
       Internal helper methods
       ======================= */

    private function findByIMEIAndDistributor(string $imei, ?int $distributorId = null): Purchase
    {
        $query = $this->queryByIMEI($imei);

        if ($distributorId !== null) {
            $query->where('user_id', $distributorId);
        }

        $priSale = $query->first();

        if (!$priSale) {
            throw new \Exception(
                "Primary Sale with IMEI: '{$imei}' not found in the system."
            );
        }

        return $priSale;
    }

    private function queryByIMEI(string $imei)
    {
        return Purchase::where('sno', $imei)
            ->orWhere('imei', $imei);
    }
    private function queryByOrder(int $order)
    {
        return Purchase::where('order_number', $order);
    }
    private function queryByBothImei(string $imei1, string $imei2)
    {
        return Purchase::where('sno', $imei1)
        ->orWhere('sno', $imei2);
    }

    public function getStockIn(array $distributorIds, $fdate, $todate)
    {
        return Purchase::select(
                'user_id',
                'product_id',
                DB::raw('SUM(quantity) as stockin')
            )
            ->whereIn('user_id', $distributorIds)
            ->whereBetween('created_at', [$fdate, $todate])
            ->groupBy('user_id', 'product_id')
            ->get()
            ->groupBy(['user_id', 'product_id']);
    }

}
