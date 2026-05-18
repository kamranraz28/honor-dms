<?php

namespace App\Repositories;

use App\Brand;
use App\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository
{
    public function all(): Collection
    {
        return Brand::all();
    }

    public function find(int $id)
    {
        $brand = Brand::findOrFail($id);
        return $brand;
    }

    public function findByName(string $name): Brand
    {
        $brand = $this->queryByName($name)->first();

        if (!$brand) {
            throw new \Exception("Brand '{$name}' not found in the system.");
        }

        return $brand;
    }

    public function ensureNotAvailableByName(string $name)
    {
        if ($this->queryByName($name)->exists()) {
            throw new DomainException("Brand '{$name}' already exists.");
        }
    }

    public function create(array $data): Brand
    {
        return Brand::create($data);
    }

    public function update(int $id, array $data): Brand
    {
        $brand = $this->find($id);
        $brand->update($data);
        return $brand;
    }

    public function delete(int $id): void
    {
        $brand = $this->find($id);
        $brand->delete();
    }

    /* =======================
    Internal helper methods
    ======================= */

    private function queryByName(string $name)
    {
        return Brand::where('name', $name);
    }

}
