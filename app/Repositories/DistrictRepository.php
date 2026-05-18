<?php

namespace App\Repositories;

use App\District;
use Illuminate\Database\Eloquent\Collection;

class DistrictRepository
{
    public function all(): Collection
    {
        return District::all();
    }
    public function findByName(string $name): District
    {
        $district = $this->queryByName($name)->first();

        if (!$district) {
            throw new \Exception("District '{$name}' not found in the system.");
        }

        return $district;
    }
    public function ensureNotAvailableByName(string $name): void
    {
        if ($this->queryByName($name)->exists()) {
            throw new \Exception(
                "District '{$name}' already exists in the system."
            );
        }
    }

    private function queryByName(string $name)
    {
        return District::where('name', $name);
    }
}
