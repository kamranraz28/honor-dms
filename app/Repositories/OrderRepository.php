<?php

namespace App\Repositories;

use App\Order;

class OrderRepository
{
    public function find($id)
    {
        return Order::findOrFail($id);
    }
    public function create(array $data)
    {
        return Order::create($data);
    }
    public function getOrdersByDateRange($from, $to)
    {
        return Order::with('orderposting.orderspostingDetails.products')->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();
    }
    public function getCompleteOrdersByDateRange($from, $to)
    {
        return Order::with('orderposting.orderspostingDetails.products')->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('status', 5)
            ->get();
    }
    public function getCompleteOrders()
    {
        return Order::with('orderposting.orderspostingDetails.products')
            ->where('status', 5)
            ->get();
    }
    public function delete($id)
    {
        return $this->find($id)->delete();
    }
    public function getPendingOrders()
    {
        return Order::where('status', 1)->get();
    }
    public function getOrdersByStatusAndUserIds(int $authUserId,array $upazilaIds,?int $status = null)
    {
        $query = Order::with('details','orderposting.OrderspostingDetails')
            ->where(function ($q) use ($authUserId, $upazilaIds) {
                $q->where('user_id', $authUserId)
                ->orWhereIn('upazila_id', $upazilaIds);
            });
        // Status logic
        if ($status !== null) {
            $query->where('status', $status);
        } else {
            $query->whereNotIn('status', [0, 7]);
        }

        return $query
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(100);
    }


}
