<?php

namespace App\Repositories;

use App\Promortdetail;

class PromortdetailRepository
{
    public function create(array $data)
    {
        return Promortdetail::create($data);
    }
    public function find(int $id)
    {
        return Promortdetail::findOrFail($id);
    }
    public function findByPromort(int $promortId)
    {
        return Promortdetail::with('promort')->where('promort_id',$promortId)->get();
    }
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function updateByPromort(int $promortId, array $data)
    {
        return Promortdetail::where('promort_id', $promortId)->update($data);
    }
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
    public function deleteByPromort(int $promortId)
    {
        return Promortdetail::where('promort_id', $promortId)->delete();
    }
    public function changeStatus(int $id)
    {
        $promortDetail = $this->find($id);
        if($promortDetail->status == true){
            $promortDetail->status = false;
        } else {
            $promortDetail->status = true;
        }
        return $promortDetail->save();
    }


}
