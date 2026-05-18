<?php

namespace App\Repositories;

use App\Models\Orderspostingdetailsimi;

class OrderPostingDetailsIMEIRepository
{
    public function findByOrder(int $order)
    {
        return $this->queryByOrder($order)->first();
    }
    private function queryByOrder(int $order)
    {
        return Orderspostingdetailsimi::where('order_number',$order);
    }
}
