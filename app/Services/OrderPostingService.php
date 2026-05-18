<?php

namespace App\Services;

use App\Orderdeletelog;
use App\Repositories\OrderPostingDetailsIMEIRepository;
use App\Repositories\OrderPostingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrdersPostingDetailRepository;
use App\Repositories\PrimarySaleRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderPostingService
{
    protected $repo;
    protected $orderRepo;
    protected $userRepo;
    protected $productRepo;
    protected $orderDetailsRepo;
    protected $primarySaleRepo;
    protected $orderDetailImeisRepo;

    public function __construct(
        OrderPostingRepository $repo,
        OrderRepository $orderRepo,
        UserRepository $userRepo,
        ProductRepository $productRepo,
        OrdersPostingDetailRepository $orderDetailsRepo,
        PrimarySaleRepository $primarySaleRepo,
        OrderPostingDetailsIMEIRepository $orderDetailImeisRepo
        )
    {
        $this->repo = $repo;
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
        $this->productRepo = $productRepo;
        $this->orderDetailsRepo = $orderDetailsRepo;
        $this->primarySaleRepo = $primarySaleRepo;
        $this->orderDetailImeisRepo = $orderDetailImeisRepo;
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function list($searchStatus = null)
    {
        $orderNumber = Session::get('orderNumber');

        // 1: Search by order number (from session)
        if ($orderNumber && ! $searchStatus) {

            $orders = $this->repo->getByOrderNumber($orderNumber);

            $status = $orders->isNotEmpty()
                ? $orders->first()->status
                : null;

            return [
                'orders' => $orders,
                'query'  => $status,
            ];
        }

        // 2: Search by status
        if ($searchStatus) {
            return [
                'orders' => $this->repo->getByStatus($searchStatus),
                'query'  => $searchStatus,
            ];
        }

        // 3: Default listing
        return [
            'orders' => $this->repo->getDefault(),
            'query'  => 1,
        ];
    }

    public function getEditData(int $id): array
    {
        $ordersposting = $this->repo->find($id);
        $orderNumber = $ordersposting->orader_number;
        $orderInfo = $this->orderRepo->find($orderNumber);
        $distributor = $this->userRepo->find($orderInfo->upazila_id);
        $productList = $this->productRepo->getIdNameModel();

        return [
            'ordersposting'      => $ordersposting,
            'productList'        => $productList,
            'orderspostings_id'  => $id,
            'distributor'        => $distributor,
        ];
    }

    public function updateOrder(int $orderPostingId, Request $request): void
    {
        DB::transaction(function () use ($orderPostingId, $request) {

            // 1: Delete removed items
            if ($request->filled('removed_ids')) {
                $removedIds = array_filter(explode(',', $request->removed_ids));
                $this->orderDetailsRepo->deleteByIds($removedIds);
            }

            $products   = $request->input('product', []);
            $quantities = $request->input('quantity', []);
            $ids        = $request->input('id', []);

            // 2 Loop by product count (source of truth)
            foreach ($products as $index => $productId) {

                // Skip empty rows
                if (empty($productId) || empty($quantities[$index])) {
                    continue;
                }

                $detailId = $ids[$index] ?? null;

                if (! empty($detailId)) {
                    // Update
                    $this->orderDetailsRepo->update(
                        (int) $detailId,
                        [
                            'product_id' => $productId,
                            'quantity'   => $quantities[$index],
                        ]
                    );
                } else {
                    // Insert new
                    $this->orderDetailsRepo->create([
                        'orderspostings_id' => $orderPostingId,
                        'product_id'        => $productId,
                        'quantity'          => $quantities[$index],
                    ]);
                }
            }
        });
    }


    public function storeOrderNumber($orderNumber)
    {
        Session::put('orderNumber', $orderNumber);
    }

    public function destroy($id)
    {
        $orderPosting = $this->repo->find($id);
        $orderNumber = $orderPosting->orader_number;
        if($this->primarySaleRepo->findByOrder($orderNumber)){
            return redirect()->back()->with('error',"Cannot delete order {$orderNumber} with associated primary sales! Please delete the primary sales first using bulk format-16");
        }
        if($this->orderDetailImeisRepo->findByOrder($orderNumber)){
            return redirect()->back()->with('error', "Cannot delete order {$orderNumber} with associated IMEI records! Please delete the IMEI records first using bulk format-203.");
        }
        $this->orderDetailsRepo->deleteByPostingId($id);
        $this->repo->delete($id);
        $this->orderRepo->delete($orderNumber);

        // Log the deletion
        Orderdeletelog::create([
            'order_number' => $orderNumber,
            'deleted_by' => auth()->id(),
        ]);

        return true;
    }

    public function statusUpdate($id, $status)
    {
        $this->repo->update($id, ['status' => $status]);
    }
}
