<?php

namespace App\Repositories;

use App\Retailer;

class RetailerRepository
{
    public function findRetailerByCode(string $officeId): Retailer
    {
        $retailer = $this->queryByCode($officeId)->first();

        if (!$retailer) {
            throw new \Exception(
                "No retailer mapping found for retailer code '{$officeId}'."
            );
        }

        return $retailer;
    }

    public function findMapping(int $distributorId, int $retailerId): Retailer
    {
        $mapping = Retailer::where('user_id', $distributorId)
            ->where('retailer_id', $retailerId)
            ->first();

        if (!$mapping) {
            throw new \Exception(
                "No mapping exists between distributor '{$distributorId}' and retailer '{$retailerId}'."
            );
        }

        return $mapping;
    }

    public function ensureNoMapping(int $retailerId): void
    {
        if (
            Retailer::where('retailer_id', $retailerId)
                ->exists()
        ) {
            throw new \Exception(
                "Mapping already exists for retailer '{$retailerId}'."
            );
        }
    }

    public function create(array $data): Retailer
    {
        return Retailer::create($data);
    }

    public function destroy(int $distributorId, int $retailerId): bool
    {
        return $this->findMapping($distributorId, $retailerId)->delete();
    }

    public function deleteByRetailerId(int $retailerId): int
    {
        return Retailer::where('retailer_id', $retailerId)->delete();
    }

    private function queryByCode(string $retailerCode)
    {
        return Retailer::where('officeid', $retailerCode);
    }
}
