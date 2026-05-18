<?php

namespace App\Repositories;

use App\Transfer;

class TransferRepository
{
    /**
     * Fetch transfers with optional date filtering
     */
    public function getTransfers($fromDate = null, $toDate = null)
    {
        $query = Transfer::with([
            'user',
            'retailer',
            'newRetailer',
            'product'
        ]);

        if ($fromDate && $toDate) {
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        return $query->get();
    }
}
