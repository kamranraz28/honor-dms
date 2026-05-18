<?php

namespace App\Services;

use App\Repositories\StockRepository;
use App\Repositories\TertiarySaleRepository;

class WarrantyActivationService
{
    protected $stockRepo;
    protected $terSaleRepo;
    public function __construct(
        StockRepository $stockRepo,
        TertiarySaleRepository $terSaleRepo
    ) {
        $this->stockRepo = $stockRepo;
        $this->terSaleRepo = $terSaleRepo;
    }
    public function activeWarranty(array $data)
    {
        $retailerId = $data['retailer_id'];
        $sno = $data['sno'];
        $mobile = $data['mobile'];
        $fdate = $data['fdate'];

        $stock = $this->stockRepo->findByIMEI($sno);
        $this->terSaleRepo->ensureNotAvailableByIMEI($sno);

        $insertData = [
            'created_at' => date('Y-m-d H:i:s', strtotime($fdate)),
            'product_id' => $stock->product_id,
            'brand_id'   => $stock->brand_id,
            'wperiod'    => $stock->wperiod,
            'imei'       => $stock->imei,
            'user_id'    => $retailerId,
            'sno'        => $sno,
            'mobile'     => $mobile,
            'promo_id'   => 0,
            'promodetail_id' => 0,
            'status'     => 0,
        ];

        return $this->terSaleRepo->create($insertData);
    }
}
