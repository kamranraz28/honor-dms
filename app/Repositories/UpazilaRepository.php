<?php

namespace App\Repositories;

use App\Upazila;
use Illuminate\Database\Eloquent\Collection;

class UpazilaRepository
{
    public function all(): Collection
    {
        return Upazila::all();
    }

    public function findByName(string $name): Upazila
    {
        $upazila = $this->queryByName($name)->first();

        if (!$upazila) {
            throw new \Exception(
                "Upazila '{$name}' not found in the system."
            );
        }

        return $upazila;
    }

    public function ensureNotAvailableByName(string $name): void
    {
        if ($this->queryByName($name)->exists()) {
            throw new \Exception(
                "Upazila '{$name}' already exists in the system."
            );
        }
    }

    private function queryByName(string $name)
    {
        return Upazila::where('name', $name);
    }

}
