<?php

namespace App\Repositories;

use App\Orderspostingdetail;

class OrdersPostingDetailRepository
{
    public function create(array $data): void
    {
        Orderspostingdetail::create($data);
    }
    public function getDetailsByPostingId($postingId)
    {
        return Orderspostingdetail::where('orderspostings_id', $postingId)
            ->with('products')
            ->get();
    }
    public function update(int $id, array $data): void
    {
        Orderspostingdetail::where('id', $id)->update($data);
    }
    public function deleteByIds(array $ids): void
    {
        Orderspostingdetail::whereIn('id', $ids)->delete();
    }
    public function deleteByPostingId(int $postingId): void
    {
        Orderspostingdetail::where('orderspostings_id', $postingId)->delete();
    }

}
