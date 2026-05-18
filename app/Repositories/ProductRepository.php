<?php

namespace App\Repositories;

use App\Exceptions\DomainException;
use App\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function all(): Collection
    {
        return Product::with(['brand', 'cat'])->get();
    }

    public function getBasic()
    {
        return Product::select('id', 'name', 'model','dp')->get();
    }

    public function find($id)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new DomainException(
                "Product not found in the system."
            );
        }
        return $product;
    }

    public function getIdNameModel(): Collection
    {
        return Product::select('id', 'name', 'model')->get();
    }

    public function findByModel(string $model): Product
    {
        $product = $this->queryByModel($model)->first();

        if (!$product) {
            throw new \Exception(
                "Product with model '{$model}' not found in the system."
            );
        }

        return $product;
    }

    public function ensureNotAvailableByModel(string $model): void
    {
        if ($this->queryByModel($model)->exists()) {
            throw new \Exception(
                "Product with model '{$model}' already exists in the system."
            );
        }
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    //Internal Helper Methods

    private function queryByModel(string $model)
    {
        return Product::where('model', $model);
    }
}
