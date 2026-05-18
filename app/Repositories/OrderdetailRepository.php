<?php

namespace App\Repositories;

use App\Orderdetail;

class OrderdetailRepository
{
    public function findByOrderNumber($orderNumber)
    {
        return Orderdetail::where('orader_number', $orderNumber)->get();
    }
    public function create(array $data)
    {
        return Orderdetail::create($data);
    }
    public function deleteByOrderNumber($orderNumber)
    {
        return Orderdetail::where('orader_number', $orderNumber)->delete();
    }
}
