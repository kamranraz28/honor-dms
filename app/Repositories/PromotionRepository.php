<?php

namespace App\Repositories;

use App\Promo;

class PromotionRepository
{
    public function getAllPromotions()
    {
        return Promo::with('promodetail')->get();
    }
    public function find(int $id)
    {
        return Promo::findOrFail($id);
    }
    public function create(array $data)
    {
        return Promo::create($data);
    }
    public function update (int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function delete(int $id)
    {
        $this->find($id)->delete();
    }
    public function changeStatus(int $id)
    {
        $promotion = $this->find($id);
        if($promotion->status == true){
            $promotion->status = false;
        } else {
            $promotion->status = true;
        }
        return $promotion->save();
    }
}
