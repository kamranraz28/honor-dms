<?php

namespace App\Repositories;

use App\Division;
use Illuminate\Database\Eloquent\Collection;

class DivisionRepository
{
    public function all(): Collection
    {
        return Division::all();
    }
    public function findByName(string $name): Division
    {
        $division = $this->queryByName($name)->first();

        if (!$division) {
            throw new \Exception("Division '{$name}' not found in the system.");
        }

        return $division;
    }
    public function ensureNotAvailableByName(string $name): void
    {
        if ($this->queryByName($name)->exists()) {
            throw new \Exception(
                "Division '{$name}' already exists in the system."
            );
        }
    }

    private function queryByName(string $name)
    {
        return Division::where('name', $name);
    }
}
