<?php

namespace App\Repositories;

use App\PrimaryTransfer;

class PrimaryTransferRepository
{
    public function all()
    {
        return PrimaryTransfer::with('olduser', 'newuser', 'transferUser');
    }
    public function filterByFromToDate($from, $to)
    {
        return $this->all()
            ->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
    }
    public function create(array $data)
    {
        return PrimaryTransfer::create($data);
    }
}
