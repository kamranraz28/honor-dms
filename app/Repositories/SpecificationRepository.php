<?php

namespace App\Repositories;

use App\Exceptions\DomainException;
use App\Specification;

class SpecificationRepository
{
    public function all()
    {
        return Specification::all();
    }
    public function find(int $id)
    {
        return Specification::findOrFail($id);
    }
    public function ensureNoSpecificationAvailableByProductId(int $productId)
    {
        $specification = Specification::where('product_id',$productId)->first();
        if($specification){
            throw new DomainException("Specification already available for this product");
        }
    }
    public function create(array $data)
    {
        $this->ensureNoSpecificationAvailableByProductId($data['product_id']);
        return Specification::create($data);
    }
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

}
