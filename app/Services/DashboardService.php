<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getDashboardData($brandId = null, $startDate = null, $endDate = null)
    {
        return $this->repo->getDashboardData($brandId, $startDate, $endDate);
    }
}
