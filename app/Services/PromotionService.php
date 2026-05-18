<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\PromodetailRepository;
use App\Repositories\PromortdetailRepository;
use App\Repositories\PromortRepository;
use App\Repositories\PromotionRepository;

class PromotionService
{
    protected $promotionRepo;
    protected $productRepo;
    protected $promodetailRepo;
    protected $promortRepo;
    protected $promortdetailRepo;
    public function __construct(
        PromotionRepository $promotionRepo,
        ProductRepository $productRepo,
        PromodetailRepository $promodetailRepo,
        PromortRepository $promortRepo,
        PromortdetailRepository $promortdetailRepo
    ) {
        $this->promotionRepo = $promotionRepo;
        $this->productRepo = $productRepo;
        $this->promodetailRepo = $promodetailRepo;
        $this->promortRepo = $promortRepo;
        $this->promortdetailRepo = $promortdetailRepo;
    }
    public function getAllPromotions()
    {
        $promotions = $this->promotionRepo->getAllPromotions();
        $products = $this->productRepo->getBasic();

        return [
            'products' => $products,
            'promotions' => $promotions
        ];
    }
    public function storePromotion(array $data)
    {
        $promo = $this->promotionRepo->create($data);

        foreach ($data['products'] as $i => $productId) {

            $product = $this->productRepo->find($productId);
            $brandId = $product->brand_id;

            $this->promodetailRepo->create([
                'promo_id'     => $promo->id,
                'product_id'   => $productId,
                'brand_id'     => $brandId,
                'amount'       => $data['amounts'][$i],
                'quantity'     => $data['quantites'][$i],
                'limitperday'  => $data['limits'][$i],
                'details'      => $data['details'][$i],
                'sdate'        => $data['sdate'],
                'edate'        => $data['edate'],
                'status'       => $data['status'],
            ]);
        }
        return true;
    }
    public function updatePromotion(int $id, array $data)
    {
        return $this->promotionRepo->update($id,$data);
    }
    public function deletePromotion(int $id)
    {
        $this->promodetailRepo->deleteByPromotion($id);
        $this->promotionRepo->delete($id);
        return true;
    }
    public function updateStatus(int $id)
    {
        return $this->promotionRepo->changeStatus($id);
    }

    public function promotionDetails(int $promotionId)
    {
        return [
            'promotions' => $this->promodetailRepo->findByPromotion($promotionId),
            'products' => $this->productRepo->getBasic()
        ];
    }
    public function addPromotionDetails(array $data)
    {
        $promotionId = $data['promo_id'];
        $promotion = $this->promotionRepo->find($promotionId);

        $this->promodetailRepo->create([
            'promo_id' => $promotionId,
            'product_id' => $data['product_id'],
            'amount' => $data['amount'],
            'limitperday' => $data['limitperday'],
            'quantity' => $data['quantity'],
            'sdate' => $promotion->sdate,
            'edate' => $promotion->edate,
            'status' => 1,
        ]);
        return true;
    }
    public function updatePromotionDetails(array $data)
    {
        $promodetailId = $data['id'];
        return $this->promodetailRepo->update($promodetailId,$data);
    }
    public function promotionDetailsDelete(int $id)
    {
        return $this->promodetailRepo->delete($id);
    }
    public function changeStatusPromoDetails(int $id)
    {
        return $this->promodetailRepo->changeStatus($id);
    }


    public function getAllPromorts()
    {
        return $this->promortRepo->getAllPromorts();
    }
    public function storePromort(array $data)
    {
        $name = $data['name'];
        $sdate  = $data['sdate'];
        $edate = $data['edate'];
        $status = $data['status'];
        $quantities = $data['quantities'];
        $details = $data['details'];
        $image = $data['image'];
        $limits = $data['limits'];

        $base64Image = base64_encode(file_get_contents($image->path()));

        $promort = $this->promortRepo->create([
            'name' => $name,
            'sdate' => $sdate,
            'edate' => $edate,
            'status' => $status,
        ]);

        foreach($details as $index => $detail) {
            $this->promortdetailRepo->create([
                'promort_id' => $promort->id,
                'limitperday' => $limits[$index],
                'quantity' => $quantities[$index],
                'details' => $details[$index],
                'status' => 1,
                'sdate' => $sdate,
                'edate' => $edate,
                'image' => $base64Image
            ]);
        }
        return true;
    }
    public function updatePromort(array $data)
    {
        $promortId = $data['id'];
        $name = $data['name'];
        $sdate  = $data['sdate'];
        $edate = $data['edate'];
        $image = $data['image'];

        $base64Image = base64_encode(file_get_contents($image->path()));

        $promort = [
            'name'  => $name,
            'sdate' => $sdate,
            'edate' => $edate,
        ];
        $this->promortRepo->update($promortId,$promort);

        $promortDetail = [
            'sdate' => $sdate,
            'edate' => $edate,
            'image' => $base64Image
        ];
        $this->promortdetailRepo->updateByPromort($promortId,$promortDetail);
        return true;
    }
    public function deletePromort(int $id)
    {
        $this->promortdetailRepo->deleteByPromort($id);
        $this->promortRepo->delete($id);
        return true;
    }
    public function promortDetails(int $promortId)
    {
        return $this->promortdetailRepo->findByPromort($promortId);
    }
    public function storePromortDetail(array $data)
    {
        $promort = $this->promortRepo->find($data['promort_id']);
        return $this->promortdetailRepo->create([
            'promort_id' => $data['promort_id'],
            'quantity' => $data['quantity'],
            'limitperday' => $data['limitperday'],
            'details' => $data['details'],
            'sdate' => $promort->sdate,
            'edate' => $promort->edate,
            'status' => 1
        ]);
    }
    public function updatePromortDetail(array $data)
    {
        $promortdetailId = $data['id'];

        $updateData = [
            'quantity' => $data['quantity'],
            'limitperday' => $data['limitperday'],
            'details' => $data['details'],
        ];
        return $this->promortdetailRepo->update($promortdetailId, $updateData);
    }
    public function deletePromortDetails(int $id)
    {
        return $this->promortdetailRepo->delete($id);
    }
    public function changeStatusPromortDetails(int $id)
    {
        return $this->promortdetailRepo->changeStatus($id);
    }
    public function updatePromortStatus(int $id)
    {
        return $this->promortRepo->changeStatus($id);
    }

}
