<?php

namespace App\Repositories;

use App\Tsoupazila;

class TsoRepository
{
    public function findByTSOAndDistributor(int $distributorId, int $tsoId)
    {
        $tso = $this->queryByTSOAndDistributor($distributorId, $tsoId)->first();
        if (!$tso) {
            throw new \Exception("There is no mapping for this.");
        }
        return $tso;
    }
    public function ensureNotAvailableByTSOAndDistributor(int $distributorId, int $tsoId)
    {
        if ($this->queryByTSOAndDistributor($distributorId, $tsoId)->exists()) {
            throw new \Exception("This mapping is already available into the system.");
        }
    }
    public function create(array $data)
    {
        return Tsoupazila::create($data);
    }

    public function deleteByTSOAndDistributor(int $distributorId, int $tsoId)
    {
        $this->queryByTSOAndDistributor($distributorId, $tsoId)->delete();
    }
    private function queryByTSOAndDistributor(int $distributorId, int $tsoId)
    {
        return Tsoupazila::where('upazila_id', $distributorId)
            ->where('user_id', $tsoId);
    }
    public function getDistributorsByTSOId(int $tsoId)
    {
        return Tsoupazila::select('id', 'name', 'bn_name', 'upazila_id')
            ->where('user_id', $tsoId)
            ->get();
    }


}
