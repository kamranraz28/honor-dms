<?php

namespace App\Repositories;

use App\Promodetail;

class PromodetailRepository
{
    public function create(array $data)
    {
        return Promodetail::create($data);
    }
    public function find(int $id)
    {
        return Promodetail::findOrFail($id);
    }
    public function update(int $id, array $data)
    {
        return $this->find($id)->update($data);
    }
    public function findByPromotion(int $promotionId)
    {
        return Promodetail::with('promo')->where('promo_id',$promotionId)->get();
    }
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
    public function deleteByPromotion(int $promotionId)
    {
        return Promodetail::where('promo_id', $promotionId)->delete();
    }
    public function changeStatus(int $id)
    {
        $promoDetail = $this->find($id);
        if($promoDetail->status == true){
            $promoDetail->status = false;
        } else {
            $promoDetail->status = true;
        }
        return $promoDetail->save();
    }


}
