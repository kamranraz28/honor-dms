<?php

namespace App\Services;

use App\Repositories\SpecificationRepository;

class SpecificationService
{
    protected $specifications;
    public function __construct(SpecificationRepository $specifications){
        $this->specifications = $specifications;
    }
    public function getAllSpecifications()
    {
        return $this->specifications->all();
    }
    public function storeSpecification(array $data)
    {
        return $this->specifications->create($data);
    }
    public function updateSpecification(int $id, array $data)
    {
        return $this->specifications->update($id, $data);
    }
    public function deleteSpecification(int $id)
    {
        return $this->specifications->delete($id);
    }
}
