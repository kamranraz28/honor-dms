<?php

namespace App\Services;

use App\Brand;
use App\Repositories\BrandRepository;
use Exception;

class BrandService
{
    protected $brands;
    public function __construct(
        BrandRepository $brands
    )
    {
        $this->brands = $brands;
    }
    public function getAllBrands()
    {
        return $this->brands->all();
    }

    public function createBrand(array $data)
    {
        $this->brands->ensureNotAvailableByName($data['name']);
        return $this->brands->create($data);
    }
    public function updateBrand(int $id, array $data)
    {
        $this->brands->ensureNotAvailableByName($data['name']);
        return $this->brands->update($id, $data);
    }
    public function deleteBrand(int $id)
    {
        $this->brands->delete($id);
    }
}
