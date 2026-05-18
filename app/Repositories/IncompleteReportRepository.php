<?php

namespace App\Repositories;

use App\Models\Orderspostingdetailsimi;
use App\Orderspostingdetail;
use App\Product;
use Illuminate\Support\Facades\DB;

class IncompleteReportRepository
{
    public function getMismatchedResults()
    {
        $results = Orderspostingdetail::with(['products', 'orderspostingdetailsimis'])
            ->whereHas('Ordersposting', function ($q) {
                $q->where('status', 5);
            })
            ->select(
                'orderspostingdetails.*',
                DB::raw('COUNT(orderspostingdetailsimis.id) as count_imei')
            )
            ->leftJoin(
                'orderspostingdetailsimis',
                'orderspostingdetails.id',
                '=',
                'orderspostingdetailsimis.orderspostingdetails_id'
            )
            ->groupBy('orderspostingdetails.id')
            ->havingRaw('COUNT(orderspostingdetailsimis.id) != orderspostingdetails.quantity')
            ->get();

        // PHP 7.1-compatible mapping
        return $results->map(function ($item) {

            // Get first IMEI record safely
            $firstImei = $item->orderspostingdetailsimis->first();
            $orderNumber = $firstImei ? $firstImei->order_number : null;

            // Get product model safely
            $product = $item->products;
            $productModel = $product ? $product->model : null;

            return [
                'orderspostingdetails_id' => $item->id,
                'quantity'                => $item->quantity,
                'order_number'            => $orderNumber ?: '-',
                'model'                   => $productModel ?: '-',
                'product_id'              => $item->product_id,
                'count'                   => $item->count_imei,
            ];
        });
    }

    public function getIncompleteIMEIView($id, $productId)
    {
        // Fetch all IMEI records
        $uploadInfo = Orderspostingdetailsimi::where('orderspostingdetails_id', $id)
            ->where('product_id', $productId)
            ->get();

        // Extract order number safely
        $first = $uploadInfo->first();
        $orderNumber = $first ? $first->order_number : '-';

        // Extract IMEI1 & IMEI2
        $imei1 = $uploadInfo->pluck('imi');
        $imei2 = $uploadInfo->pluck('imi2');

        // Fetch product model
        $product = Product::select('model')
            ->where('id', $productId)
            ->first();

        $model = $product ? $product->model : '-';

        return [
            'orderNumber' => $orderNumber,
            'imei1'       => $imei1,
            'imei2'       => $imei2,
            'model'       => $model,
        ];
    }

}
