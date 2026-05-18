<?php

namespace App\Repositories;

use App\Exceptions\DomainException;
use App\Stock;

class StockRepository
{
    public function paginate($perPage = 100)
    {
        return Stock::paginate($perPage);
    }
    public function getCurrentStocksByDate(string $fdate, string $todate)
    {
        return Stock::with('product')
            ->where('details', NULL)->whereBetween('created_at',[$fdate, $todate])
            ->get();
    }
    public function getFullCurrentStocks()
    {
        return Stock::with('product')
            ->where('details', NULL)
            ->get();
    }
    public function find($id)
    {
        $stock = Stock::find($id);
        if (!$stock) {
            throw new DomainException(
                "Stock not found in the system."
            );
        }
        return $stock;
    }
    public function create(array $data): Stock
    {
        return Stock::create($data);
    }
    public function update($id, array $data): Stock
    {
        $stock = $this->find($id);
        $stock->update($data);
        return $stock;
    }
    public function findByIMEI(string $imei): Stock
    {
        $stock = $this->queryByImei($imei)->first();

        if (!$stock) {
            throw new DomainException(
                "Stock with IMEI '{$imei}' not found in the system."
            );
        }

        return $stock;
    }

    public function findByBothIMEI(string $imei1, string $imei2): Stock
    {
        $stock = $this->queryByBothImei($imei1, $imei2)->first();

        if (!$stock) {
            throw new DomainException(
                "Stock with IMEIs '{$imei1}' and '{$imei2}' not found in the system."
            );
        }

        return $stock;
    }

    public function getStocksByIMEIs(array $imeis)
    {
        return Stock::whereIn('sno', $imeis)
            ->orWhereIn('imei', $imeis)
            ->get();
    }


    public function ensureNotAvailableByIMEI(string $imei): void
    {
        if ($this->queryByImei($imei)->exists()) {
            throw new DomainException(
                "Stock with IMEI '{$imei}' already exists in the system."
            );
        }
    }

    public function destroy(string $imei)
    {
        return $this->findByIMEI($imei)->delete();
    }

    private function queryByImei(string $imei)
    {
        return Stock::where('sno', $imei)->orWhere('imei', $imei);
    }

    private function queryByBothImei(string $imei1, string $imei2)
    {
        return Stock::where('sno', $imei1)
        ->orWhere('sno', $imei2);
    }
    public function delete($id)
    {
        $stock = $this->find($id);
        return $stock->delete();
    }
    public function filterByDateRange($fdate, $tdate, $perPage = 100)
    {
        $start = $fdate . ' 00:00:00';
        $end   = $tdate . ' 23:59:59';

        return Stock::whereBetween('created_at', [$start, $end])
                    ->paginate($perPage);
    }

    public function filterByDateRangeAll($fdate, $tdate)
    {
        $start = $fdate . ' 00:00:00';
        $end   = $tdate . ' 23:59:59';

        return Stock::whereBetween('created_at', [$start, $end])
                    ->with('brand', 'product')
                    ->get();
    }

    public function checkStockByImeiWithProduct(string $imei)
    {
        return Stock::with('product')
            ->where('sno',$imei)
            ->orWhere('imei', $imei)
            ->first();
    }


}
