<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categories;
    public function __construct(CategoryRepository $categories){
        $this->categories = $categories;
    }
    public function getAllCategories()
    {
        return $this->categories->all();
    }
    public function storeCategory(array $data)
    {
        $this->categories->ensureNotAvailableByName($data['name']);
        return $this->categories->create($data);
    }
    public function updateCategory(int $id, array $data)
    {
        $this->categories->ensureNotAvailableByName($data['name']);
        return $this->categories->update($id,$data);
    }
    public function deleteCategory(int $id)
    {
        return $this->categories->delete($id);
    }
}
