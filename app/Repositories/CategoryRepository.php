<?php

namespace App\Repositories;

use App\Cat;
use App\Exceptions\DomainException;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function all(): Collection
    {
        return Cat::all();
    }
    public function find(int $id)
    {
        return Cat::findOrFail($id);
    }

    public function findByName(string $name): Cat
    {
        $category = $this->queryByName($name)->first();

        if (!$category) {
            throw new \Exception("Category '{$name}' not found in the system.");
        }

        return $category;
    }

    public function ensureNotAvailableByName(string $name)
    {
        if ($this->queryByName($name)->exists()) {
            throw new DomainException("Category '{$name}' already exists.");
        }
    }

    public function create(array $data): Cat
    {
        return Cat::create($data);
    }
    public function update(int $id, array $data)
    {
        $this->find($id)->update($data);
    }
    public function delete(int $id)
    {
        $this->find($id)->delete();
    }

    //Internal Helper Methods

    private function queryByName(string $name)
    {
        return Cat::where('name', $name);
    }
}
