<?php

namespace App\Repositories;

use App\Models\Ordersposting;
use App\Exceptions\DomainException;

class OrderPostingRepository
{
    public function find(int $id)
    {
        return Ordersposting::findOrFail($id);
    }
    public function getByOrderNumber($orderNumber)
    {
        $orderPosting = Ordersposting::where('orader_number', $orderNumber)
            ->paginate();
        if(!$orderPosting) {
            throw new DomainException("Order Posting not found for order number: " . $orderNumber);
        }
        return $orderPosting;
    }

    public function getByStatus($status)
    {
        return Ordersposting::where('status', $status)
            ->orderBy('status')
            ->paginate();
    }

    public function getPostingsByOrderIds($orderIds)
    {
        return Ordersposting::whereIn('orader_number', $orderIds)->get();
    }

    public function getOrderPostingsWithDetails($orderIds)
    {
        return Ordersposting::whereIn('orader_number', $orderIds)
            ->with([
                'Order.users',      // load customer
                'OrderspostingDetails.products'  // load product details
            ])
            ->get();
    }

    public function getDefault()
    {
        return Ordersposting::where('status', 1)
            ->orderBy('status')
            ->paginate();
    }

    public function update(int $id, array $data)
    {
        $orderPosting = $this->find($id);
        $orderPosting->update($data);
    }
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

}
