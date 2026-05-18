<?php

namespace App\Repositories;

use App\Models\Orderspostingdetailsimi;

class ImeiUploadRepository
{
    public function findByImei(string $imei)
    {
        $orderPosting = $this->queryByImei($imei)->first();
        if(!$orderPosting){
            throw new \Exception("There is no data for IMEI '{$imei}' ");
        }
        return $orderPosting;
    }
    public function create(array $data)
    {
        return Orderspostingdetailsimi::create($data);
    }

    private function queryByImei(string $imei)
    {
        return Orderspostingdetailsimi::where('imi',$imei)->orWhere('imi2',$imei);
    }
    private function queryByOrder(int $orderId)
    {
        return Orderspostingdetailsimi::where('order_number',$orderId);
    }
    public function destroy(string $imei)
    {
        $this->findByImei($imei)->delete();
    }

}
