<?php

namespace App\Repositories;

use App\Promortkey;

class PromortkeyRepository
{
    public function all()
    {
        return Promortkey::all();
    }
    public function find(int $id)
    {
        return Promortkey::find($id);
    }
    public function create(array $data)
    {
        return Promortkey::create($data);
    }
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function delete(int $id)
    {
        return $this->find($id)->delete();
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
