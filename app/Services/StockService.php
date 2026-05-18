<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;

class StockService
{
    protected $stockRepository;
    protected $productRepository;
    public function __construct( StockRepository $stockRepository, ProductRepository $productRepository)
    {
        $this->stockRepository = $stockRepository;
        $this->productRepository = $productRepository;
    }

    public function paginateStocks($perPage = 100)
    {
        $products = $this->productRepository->all();
        $stocks   = $this->stockRepository->paginate($perPage);

        return [
            'products' => $products,
            'stocks'   => $stocks,
        ];
    }
    public function createStock(array $data)
    {
        $product_id = $data['product_id'];
        $imeis      = $data['imeis'];
        $snos       = $data['snos'];
        $wperiods   = $data['wperiods'];

        // Get product brand ID
        $product = $this->productRepository->find($product_id);
        $brand_id = $product->brand_id;

        foreach ($snos as $index => $sno) {
            $imei = $imeis[$index] ?? null;
            $this->stockRepository->ensureNotAvailableByIMEI($imei);
            $this->stockRepository->ensureNotAvailableByIMEI($sno);
            $wperiod = $wperiods[$index] ?? null;

            $stockData = [
                'product_id' => $product_id,
                'brand_id'   => $brand_id,
                'sno'        => $sno,
                'imei'       => $imei,
                'wperiod'    => $wperiod,
            ];

            $this->stockRepository->create($stockData);
        }
        return true;
    }

    public function updateStock($id, array $data)
    {
        $this->stockRepository->find($id);
        return $this->stockRepository->update($id, $data);
    }
    public function deleteStock($id)
    {
        return $this->stockRepository->delete($id);
    }

    public function filterStocksByDateRange($fdate, $tdate)
    {
        $products = $this->productRepository->all();
        $stocks = $this->stockRepository->filterByDateRange($fdate, $tdate);

        return [
            'products' => $products,
            'stocks'   => $stocks,
        ];

    }


}
