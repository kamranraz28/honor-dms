<?php

namespace App\Repositories;

use App\Promort;

class PromortRepository
{
    public function getAllPromorts()
    {
        return Promort::with('promortdetail')->get();
    }
    public function create(array $data)
    {
        return Promort::create($data);
    }
    public function find(int $id)
    {
        return Promort::findOrFail($id);
    }
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function delete(int $id)
    {
        $this->find($id)->delete();
    }
    public function changeStatus(int $id)
    {
        $promort = $this->find($id);
        if($promort->status == true){
            $promort->status = false;
        } else {
            $promort->status = true;
        }
        return $promort->save();
    }
}
