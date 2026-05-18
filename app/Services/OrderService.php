<?php

namespace App\Services;

use App\Repositories\OrderdetailRepository;
use App\Repositories\OrderPostingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TsoRepository;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    protected $repo;
    protected $postingRepo;
    protected $tsoRepo;
    protected $productRepo;
    protected $orderdetailRepo;

    public function __construct(OrderRepository $repo, TsoRepository $tsoRepo, ProductRepository $productRepo, OrderdetailRepository $orderdetailRepo, OrderPostingRepository $postingRepo)
    {
        $this->repo = $repo;
        $this->tsoRepo = $tsoRepo;
        $this->productRepo = $productRepo;
        $this->orderdetailRepo = $orderdetailRepo;
        $this->postingRepo = $postingRepo;
    }
    public function find($id)
    {
        return $this->repo->find($id);
    }
    public function tsoOrderList(?int $query = null)
    {
        $upazilas = $this->tsoRepo->getDistributorsByTSOId(Auth::id());

        $upazilaIds = $upazilas->pluck('upazila_id')->toArray();

        return $this->repo->getOrdersByStatusAndUserIds(
            Auth::id(),
            $upazilaIds,
            $query
        );
    }

    public function createTsoOrder()
    {
        $products = $this->productRepo->getBasic();
        $distributors = $this->tsoRepo->getDistributorsByTSOId(Auth::id());

        return [
            'products' => $products,
            'distributors' => $distributors
        ];
    }
    public function storeTsoOrder(array $data)
    {
        $models     = $data['model'];      // array of product IDs
        $quantities = $data['quintity'];   // array of quantities
        $upazila_id = $data['upazila_id'];
        $remarks    = $data['remarks'] ?? null;

        $order = $this->repo->create([
            'upazila_id' => $upazila_id,
            'user_id'    => Auth::id(),
            'remarks'    => $remarks,
        ]);

        foreach ($models as $index => $modelId) {

            $product = $this->productRepo->find($modelId); // single model ✅

            $this->orderdetailRepo->create([
                'orader_number' => $order->id,
                'product_id'    => $product->id,
                'discount'      => 0,
                'price'         => $product->dp,
                'quantity'      => $quantities[$index], // ✅ correct
                'quantity_acc'  => 0,
            ]);
        }

        return $order;
    }

    public function orderDetails($orader_no)
    {
        $order = $this->repo->find($orader_no);
        $orderDetails = $this->orderdetailRepo->findByOrderNumber($orader_no);
        $postings = $this->postingRepo->getByOrderNumber($orader_no);

        return [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'postings' => $postings
        ];
    }
    public function orderDelete($orader_no)
    {
        $this->orderdetailRepo->deleteByOrderNumber($orader_no);
        $this->repo->delete($orader_no);
        return true;
    }

}
