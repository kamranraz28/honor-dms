<?php

namespace App\Services;

use App\Exceptions\DomainException;
use App\JobProgress;
use App\Jobs\DailySalesReport;
use App\Jobs\GenerateRetailerImeiStockReport;
use App\Purchase;
use App\Repositories\IncompleteReportRepository;
use App\Repositories\OrderPostingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrdersPostingDetailRepository;
use App\Repositories\PrimarySaleRepository;
use App\Repositories\PrimaryTransferRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReplaceRepository;
use App\Repositories\SecondarySaleRepository;
use App\Repositories\StockRepository;
use App\Repositories\TertiarySaleRepository;
use App\Repositories\TransferRepository;
use App\Repositories\UserRepository;
use App\Sale;
use App\Smsdetail;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportService
{
    protected $primaryTransferRepo;
    protected $orderRepo;
    protected $orderPostingRepo;
    protected $orderPostingDetailRepo;
    protected $transferRepo;
    protected $excelService;
    protected $incompleteRepo;
    protected $terSaleRepo;
    protected $replaceRepo;
    protected $stockRepo;
    protected $productRepo;
    protected $priSaleRepo;
    protected $secSaleRepo;
    protected $userRepo;
    public function __construct(
        PrimaryTransferRepository $primaryTransferRepo,
        OrderRepository $orderRepo,
        OrderPostingRepository $orderPostingRepo,
        OrdersPostingDetailRepository $orderPostingDetailRepo,
        TransferRepository $transferRepo,
        ExcelDownloadService $excelService,
        IncompleteReportRepository $incompleteRepo,
        TertiarySaleRepository $terSaleRepo,
        ReplaceRepository $replaceRepo,
        StockRepository $stockRepo,
        ProductRepository $productRepo,
        PrimarySaleRepository $priSaleRepo,
        SecondarySaleRepository $secSaleRepo,
        UserRepository $userRepo
    ) {
        $this->primaryTransferRepo = $primaryTransferRepo;
        $this->orderRepo = $orderRepo;
        $this->orderPostingRepo = $orderPostingRepo;
        $this->orderPostingDetailRepo = $orderPostingDetailRepo;
        $this->transferRepo = $transferRepo;
        $this->excelService = $excelService;
        $this->incompleteRepo = $incompleteRepo;
        $this->terSaleRepo = $terSaleRepo;
        $this->replaceRepo = $replaceRepo;
        $this->stockRepo = $stockRepo;
        $this->productRepo = $productRepo;
        $this->priSaleRepo = $priSaleRepo;
        $this->secSaleRepo = $secSaleRepo;
        $this->userRepo = $userRepo;
    }
    public function primaryTransferReport($from, $to)
    {
        $from = date('Y-m-d', strtotime($from));
        $to = date('Y-m-d', strtotime($to));

        $transfers = $this->primaryTransferRepo
            ->filterByFromToDate($from, $to)
            ->get();

        $exportData = [];

        foreach ($transfers as $index => $pt) {
            $exportData[] = [
                '#' => $index + 1,
                'Old LD' => $pt->olduser->firstname ?? '-',
                'New LD' => $pt->newuser->firstname ?? '-',
                'IMEI-1' => $pt->imei1 ?? '-',
                'IMEI-2' => $pt->imei2 ?? '-',
                'Transfer By' => $pt->transferUser->firstname ?? '-',
                'Transfer Date' => optional($pt->created_at)->format('d-m-Y H:i'),
            ];
        }

        return $this->excelService->download('Primary_transfer_report', $exportData);
    }


    public function getPendingOrders()
    {
        $report = [];

        // 1. Fetch all pending orders
        $orders = $this->orderRepo->getPendingOrders();

        // 2. Fetch associated postings
        $postings = $this->orderPostingRepo->getPostingsByOrderIds($orders->pluck('id'));

        foreach ($postings as $posting) {

            // 3. Fetch posting details with product relation
            $details = $this->orderPostingDetailRepo->getDetailsByPostingId($posting->id);

            foreach ($details as $detail) {
                $report[] = [
                    'orderNumber' => $posting->orader_number,
                    'customerName' => $posting->Order->users->firstname ?? '',
                    'productModel' => $detail->products->model ?? '',
                    'quantity' => $detail->quantity ?? '',
                    'status' => $posting->Order->status ?? '',
                ];
            }
        }
        return $report;
    }

    public function getTodaysOrderReport($fromDate, $toDate)
    {
        $report = [];

        // 1. Fetch orders
        $orders = $this->orderRepo->getOrdersByDateRange($fromDate, $toDate);

        // 2. Fetch order postings + details
        $postings = $this->orderPostingRepo->getOrderPostingsWithDetails($orders->pluck('id'));

        // 3. Build formatted report array
        foreach ($postings as $posting) {
            foreach ($posting->OrderspostingDetails as $detail) {
                $report[] = [
                    'orderNumber' => $posting->orader_number,
                    'customerName' => $posting->order->users->firstname ?? '',
                    'productModel' => $detail->products->model ?? '',
                    'quantity' => $detail->quantity,
                    'price' => $detail->price * $detail->quantity,
                    'status' => $posting->order->status ?? '',
                ];
            }
        }

        return $report;
    }

    public function getTodaysOrderReportProductWise()
    {
        $report = [];

        $today = date('Y-m-d');

        // Fetch all today's orders including posting and details
        $orders = $this->orderRepo->getOrdersByDateRange($today, $today);

        foreach ($orders as $order) {
            if (!$order->orderposting)
                continue;

            foreach ($order->orderposting->orderspostingDetails as $detail) {

                $product = $detail->products;
                if (!$product)
                    continue;

                $productKey = $product->id;

                // Initialize row if not exists
                if (!isset($report[$productKey])) {
                    $report[$productKey] = [
                        'model' => $product->model,
                        'quantity' => 0,
                        'value' => 0,
                    ];
                }

                // Add quantity + value
                $report[$productKey]['quantity'] += $detail->quantity;
                $report[$productKey]['value'] += ($detail->quantity * $detail->price);
            }
        }

        return array_values($report);
    }


    public function secondaryTransfers($fromDate, $toDate)
    {
        $transfers = $this->transferRepo->getTransfers($fromDate, $toDate);

        $exportData = [];

        foreach ($transfers as $index => $value) {

            $exportData[] = [
                '#' => $index + 1,
                'Distributor Name' => $value->user->firstname ?? '-',
                'Previous Retailer' => $value->retailer->name ?? '-',
                'New Retailer' => $value->newRetailer->firstname ?? '-',
                'Product Name' => $value->product->name ?? '-',
                'Product Model' => $value->product->model ?? '-',
                'IMEI-1' => $value->sno ?? '-',
                'IMEI-2' => $value->imei ?? '-',
                'Secondary Date' => $value->sale_date
                    ? Carbon::parse($value->sale_date)->format('d-m-Y')
                    : '-',
                'Transfer Date' => optional($value->created_at)->format('d-m-Y H:i'),
            ];
        }

        return $this->excelService->download('Secondary_transfer_report', $exportData);
    }

    public function getIncompleteReport()
    {
        return $this->incompleteRepo->getMismatchedResults();
    }
    public function getIncompleteIMEIView($id, $productId)
    {
        return $this->incompleteRepo->getIncompleteIMEIView($id, $productId);
    }
    public function tertiarySalesReport(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];
        $brandId = $data['brand_id'];
        $sno = $data['sno'];

        // BASE QUERY WITH EAGER LOADING (BEST SPEED IMPROVEMENT)
        $terSaleQuery = $this->terSaleRepo->findByDateRange($fdate, $todate)
            ->with([
                'brand:id,name',
                'product:id,name,model',
                'secondary.user:id,officeid,firstname',
                'user:id,officeid,firstname',
            ])
            ->select([
                'id',
                'brand_id',
                'product_id',
                'sno',
                'imei',
                'wperiod',
                'user_id',
                'mobile',
                'created_at'
            ]);

        if ($brandId) {
            $terSaleQuery->where('brand_id', $brandId);
        }

        if ($sno) {
            $terSaleQuery->where(function ($q) use ($sno) {
                $q->where('sno', $sno)->orWhere('imei', $sno);
            });
        }

        // Get the result normal way (your way)
        $terSales = $terSaleQuery->get();

        $exportData = [];

        foreach ($terSales as $index => $terSale) {

            // Cache variables (faster than calling relations repeatedly)
            $brand = $terSale->brand->name ?? '-';
            $product = $terSale->product;
            $secondary = $terSale->secondary->user ?? null;
            $retailer = $terSale->user ?? null;

            // Precompute dates (fastest)
            $createdDate = $terSale->created_at->format('Y-m-d');
            $warrantyEnd = $terSale->created_at->copy()->addDays($terSale->wperiod)->format('Y-m-d');

            $exportData[] = [
                '#' => $index + 1,
                'Brand' => $brand,
                'Product Name' => $product->name ?? '-',
                'Model' => $product->model ?? '-',
                'IMEI-1' => $terSale->sno ?? '-',
                'IMEI-2' => $terSale->imei ?? '-',
                'Warranty Period' => ($terSale->wperiod ?? 0) . ' Days',
                'Sale Date' => $createdDate,
                'Warranty Start Date' => $createdDate,
                'Warranty End Date' => $warrantyEnd,
                'LD Code' => $secondary->officeid ?? '-',
                'LD Name' => $secondary->firstname ?? '-',
                'Retailer Code' => $retailer->officeid ?? '-',
                'Retailer Name' => $retailer->firstname ?? '-',
                'Mobile' => $terSale->mobile ?? '-',
                'Created' => $createdDate,
            ];
        }

        return $this->excelService->download('Tertiary_Sales_Report', $exportData);
    }
    public function fullTertiaryReport()
    {
        $terSales = $this->terSaleRepo->getAllTertiaries();
        $exportData = [];

        foreach ($terSales as $terSale) {
            $exportData[] = [
                'Retailer Name' => $terSale->user->firstname ?? '-',
                'Retailer ID' => $terSale->user->officeid ?? '-',
                'Brand' => $terSale->brand->name ?? '-',
                'Product' => $terSale->product->name ?? '-',
                'Model' => $terSale->product->model ?? '-',
                'IMEI 1' => $terSale->sno ?? '-',
                'IMEI 2' => $terSale->imei ?? '-',
                'Customer Mobile' => $terSale->mobile ?? '-',
                'Warranty Period' => $terSale->wperiod ?? '-',
                'Sale Date' => $terSale->created_at->format('Y-m-d') ?? '-',
                'Warranty S. Date' => $terSale->created_at->format('Y-m-d') ?? '-',
                'Warranty E. Date' => Carbon::parse($terSale->created_at)->addDays($terSale->wperiod)->format('Y-m-d') ?? '-',
            ];
        }
        return $this->excelService->download('Tertiary_Sales_Report', $exportData);
    }
    public function tertiaryReportByDate(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $terSales = $this->terSaleRepo->getTertiaryByDateRange($fdate, $todate);
        $exportData = [];

        foreach ($terSales as $terSale) {
            $exportData[] = [
                'Retailer Name' => $terSale->user->firstname ?? '-',
                'Retailer ID' => $terSale->user->officeid ?? '-',
                'Brand' => $terSale->brand->name ?? '-',
                'Product' => $terSale->product->name ?? '-',
                'Model' => $terSale->product->model ?? '-',
                'IMEI 1' => $terSale->sno ?? '-',
                'IMEI 2' => $terSale->imei ?? '-',
                'Customer Mobile' => $terSale->mobile ?? '-',
                'Warranty Period' => $terSale->wperiod ?? '-',
                'Sale Date' => $terSale->created_at->format('Y-m-d') ?? '-',
                'Warranty S. Date' => $terSale->created_at->format('Y-m-d') ?? '-',
                'Warranty E. Date' => Carbon::parse($terSale->created_at)->addDays($terSale->wperiod)->format('Y-m-d') ?? '-',
            ];
        }
        return $this->excelService->download('Tertiary_Sales_Report', $exportData);
    }


    public function replaceReport(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $replaces = $this->replaceRepo->findByDateRange($fdate, $todate)
            ->with([
                'user:id,firstname,officeid',
                'smsdetail.user:id,firstname,officeid',
                'smsdetail.product:id,name,model',
            ])->get();

        $exportData = [];

        foreach ($replaces as $index => $r) {
            $exportData[] = [
                'Distributor Name' => optional($r->user)->firstname ?? '-',
                'Distributor Code' => optional($r->user)->officeid ?? '-',
                'Retailer Name' => optional(optional($r->smsdetail)->user)->firstname ?? '-',
                'Retailer Code' => optional(optional($r->smsdetail)->user)->officeid ?? '-',
                'Previous IMEI 1' => $r->sno,
                'Previous IMEI 2' => $r->imei,
                'Sale Date' => optional($r->smsdetail)->saledate,
                'Warranty Start Date' => optional($r->smsdetail)->sdate,
                'Warranty End Date' => optional($r->smsdetail)->edate,
                'Warranty Period' => optional($r->smsdetail)->wperiod
                    ? optional($r->smsdetail)->wperiod . ' Days'
                    : null,
                'Replace Date' => $r->created_at->format('m/d/Y'),
                'Problem' => $r->problem,
                'Type' => $r->service_type,
                'Product' => optional(optional($r->smsdetail)->product)->name,
                'Model' => optional(optional($r->smsdetail)->product)->model,
                'Replaced IMEI 1' => optional($r->smsdetail)->sno,
                'Replaced IMEI 2' => optional($r->smsdetail)->imei,
                'Received Date at Salextra' => $r->received,
                'Void' => $r->void,
                'Status' => $r->service_status,
                'Delivery Date' => $r->delivery_date,
                'Remarks' => $r->remarks,
                'Memo' => $r->memo
                    ? asset('storage/app/d/nokia/' . $r->memo)
                    : null,
            ];
        }
        return $this->excelService->download('Replace_report', $exportData);
    }

    public function currentMonthStockReport()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $stocks = $this->stockRepo->filterByDateRangeAll($startDate, $endDate);
        $exportData = [];
        foreach ($stocks as $index => $stock) {
            $exportData[] = [
                'Brand Name' => $stock->brand->name ?? '-',
                'Product Name' => $stock->product->name ?? '-',
                'Product Model' => $stock->product->model ?? '-',
                'IMEI-1' => $stock->sno ?? '-',
                'IMEI-2' => $stock->imei ?? '-',
                'Receive Date' => $stock->created_at->format('Y-m-d') ?? '-',
            ];
        }
        $currentMonthName = now()->format("F");         // January
        $currentYearShort = now()->format("y");         // 26

        $fileName = "Stock_Report - {$currentMonthName}'{$currentYearShort}";

        return $this->excelService->download($fileName, $exportData);

    }
    public function stockReportDownload(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $stocks = $this->stockRepo->filterByDateRangeAll($fdate, $todate);

        $exportData = [];
        foreach ($stocks as $index => $stock) {
            $exportData[] = [
                'Brand Name' => $stock->brand->name ?? '-',
                'Product Name' => $stock->product->name ?? '-',
                'Product Model' => $stock->product->model ?? '-',
                'IMEI-1' => $stock->sno ?? '-',
                'IMEI-2' => $stock->imei ?? '-',
                'Receive Date' => $stock->created_at->format('Y-m-d') ?? '-',
            ];
        }

        return $this->excelService->download('Stock_report', $exportData);

    }

    public function retailerImeiStockReportDownload()
    {
        $jobId = (string) Str::uuid();
        $filePath = "exports/retailer_stock_report_job_{$jobId}.xlsx";

        // Initialize job tracking
        $job = JobProgress::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'type' => 'retailer_stock_report',
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
            'file_path' => $filePath,
        ]);

        GenerateRetailerImeiStockReport::dispatch($filePath, $jobId);
        return $job;
    }
    public function downloadGeneratedStockReport($jobId)
    {
        $job = JobProgress::find($jobId);
        if (!$job || $job->status !== 'completed') {
            throw new DomainException('Report is not ready for download yet.');
        }

        $filePath = storage_path('app/' . $job->file_path);
        if (!file_exists($filePath)) {
            throw new DomainException('Report file doesn\'t exist.');
        }

        return response()->download($filePath, basename($filePath))
            ->deleteFileAfterSend(true);
    }

    public function distributorImeiStockReportDownload()
    {
        $rows = DB::table('purchases as t1')
            ->select(
                't1.sno',
                't1.imei',
                't2.firstname as distributor',
                't2.officeid',
                't4.name as brand',
                't3.name as product',
                't3.model'
            )
            ->join('users as t2', 't1.user_id', '=', 't2.id')
            ->join('brands as t4', 't1.brand_id', '=', 't4.id')
            ->join('products as t3', 't1.product_id', '=', 't3.id')
            ->whereNotIn('t1.sno', function ($q) {
                $q->select('sno')->from('sales');
            })
            ->whereNotIn('t1.sno', function ($q) {
                $q->select('sno')->from('smsdetails');
            })
            ->orderBy('t1.id', 'desc')
            ->cursor(); // Use cursor for memory efficiency

        $exportData = [];
        foreach ($rows as $index => $row) {
            $exportData[] = [
                '#' => $index + 1,
                'Distributor' => $row->distributor,
                'Distributor Code' => $row->officeid,
                'Brand' => $row->brand,
                'Product' => $row->product,
                'Product Model' => $row->model,
                'IMEI 1' => $row->sno,
                'IMEI 2' => $row->imei ?: '-',
            ];
        }
        return $this->excelService->download('Distributor_IMEI_Stock_Report', $exportData);
    }

    public function distributorStockReportDownload(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];
        $distributorId = $data['distributor_id'];

        // Fetch Core Data
        $products = $this->productRepo->getBasic();
        $stockIn = $this->priSaleRepo->getStockIn($distributorId, $fdate, $todate);
        $stockOut = $this->secSaleRepo->getStockOut($distributorId, $fdate, $todate);
        $distName = $this->userRepo->getDistributorName($distributorId);

        // Build Final Report
        $exportData = [];

        foreach ($products as $index => $product) {

            $in = $stockIn[$product->id] ?? 0;
            $out = $stockOut[$product->id] ?? 0;

            $exportData[] = [
                '#' => $index + 1,
                'Distributor' => $distName,
                'Product Name' => $product->name,
                'Product Model' => $product->model,
                'Stock In' => $in,
                'Stock Out' => $out,
                'Stock' => $in - $out,
            ];
        }
        return $this->excelService->download('Distributor_Stock_Report_', $exportData);
    }

    public function distributorDetailsStockReport(array $data)
    {
        $distributorId = $data['distributor_id'] ?? null;

        $fdate = Carbon::parse('2019-01-01')->startOfDay();
        $todate = Carbon::parse($data['todate'])->endOfDay();

        // Products
        $products = $this->productRepo->getBasic();

        // All or specific distributor
        $distributors = $this->userRepo->getDistributors($distributorId);
        $distIds = $distributors->pluck('id')->toArray();

        // Stock In / Out grouped by distributor_id → product_id
        $stockIn = $this->priSaleRepo->getStockIn($distIds, $fdate, $todate);
        $stockOut = $this->secSaleRepo->getStockOut($distIds, $fdate, $todate);

        $exportData = [];

        foreach ($distributors as $index => $distributor) {

            $rowData = [
                '#' => $index + 1,
                'Distributor Name' => $distributor->firstname ?? '-',
                'Distributor Code' => $distributor->officeid ?? '-',
                'Total' => 0,
            ];

            $total = 0;

            foreach ($products as $product) {

                $productId = $product->id;

                // CORRECT LOOKUP (Distributor + Product)
                $stockInQty = $stockIn[$distributor->id][$productId][0]->stockin ?? 0;
                $stockOutQty = $stockOut[$distributor->id][$productId][0]->stockout ?? 0;

                $balance = $stockInQty - $stockOutQty;
                $total += $balance;

                $rowData[$product->model] = $balance;
            }

            $rowData['Total'] = $total;

            $exportData[] = $rowData;
        }

        return $this->excelService->download('Distributor_Details_Stock_Report_', $exportData);
    }

    public function vatReport(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $orders = $this->orderRepo->getCompleteOrdersByDateRange($fdate, $todate);

        $exportData = [];

        foreach ($orders as $order) {
            if (!$order->orderposting)
                continue;

            foreach ($order->orderposting->orderspostingDetails as $detail) {
                $exportData[] = [
                    'CompanyCode' => 'SXN01',
                    'BranchCode' => '0002',
                    'InvoiceNo' => $order->orderposting->id . Carbon::parse($order->updated_at)->format('dmY'),
                    'CustomerCode' => $order->user->officeid ?? '',
                    'IssueDate' => $order->created_at->format('Y-m-d'),
                    'IssueTime' => $order->created_at->format('H:i:s'),
                    'DeliveryDate' => $order->updated_at->format('Y-m-d'),
                    'DeliveryTime' => $order->updated_at->format('H:i:s'),
                    'Place' => $order->user->address ?? '',
                    'Car' => $order->orderposting->delivery_info ?? '',
                    'Remarks' => $order->orderposting->remarks ?? '',
                    'ChallanType' => $detail->products->chalan_type ?? '',
                    'DistChanel' => '',
                    'ErrorMessage' => '',
                    'ProductCode' => $detail->products->product_code,
                    'IssueQty' => $detail->quantity,
                    'UnitTP' => '',
                    'TotalWithoutSD' => '',
                    'TotalSD' => '',
                    'TotalWithoutVat' => '',
                    'TotalVat' => '',
                    'TotalWithVat' => '',
                    'NetAmount' => '',
                    'Discount' => '',
                ];
            }
        }
        return $this->excelService->download('Vat_Report', $exportData);
    }

    public function fullVatReport()
    {

        $orders = $this->orderRepo->getCompleteOrders();

        $exportData = [];

        foreach ($orders as $order) {
            if (!$order->orderposting)
                continue;

            foreach ($order->orderposting->orderspostingDetails as $detail) {
                $exportData[] = [
                    'CompanyCode' => 'SXN01',
                    'BranchCode' => '0002',
                    'InvoiceNo' => $order->orderposting->id . Carbon::parse($order->updated_at)->format('dmY'),
                    'CustomerCode' => $order->user->officeid ?? '',
                    'IssueDate' => $order->created_at->format('Y-m-d'),
                    'IssueTime' => $order->created_at->format('H:i:s'),
                    'DeliveryDate' => $order->updated_at->format('Y-m-d'),
                    'DeliveryTime' => $order->updated_at->format('H:i:s'),
                    'Place' => $order->user->address ?? '',
                    'Car' => $order->orderposting->delivery_info ?? '',
                    'Remarks' => $order->orderposting->remarks ?? '',
                    'ChallanType' => $detail->products->chalan_type ?? '',
                    'DistChanel' => '',
                    'ErrorMessage' => '',
                    'ProductCode' => $detail->products->product_code,
                    'IssueQty' => $detail->quantity,
                    'UnitTP' => '',
                    'TotalWithoutSD' => '',
                    'TotalSD' => '',
                    'TotalWithoutVat' => '',
                    'TotalVat' => '',
                    'TotalWithVat' => '',
                    'NetAmount' => '',
                    'Discount' => '',
                ];
            }
        }
        return $this->excelService->download('Vat_Report', $exportData);
    }

    public function fullPrimaryReport()
    {
        $priSales = $this->priSaleRepo->getAllPrimaries();
        $exportData = [];

        foreach ($priSales as $priSale) {
            $price = optional(optional($priSale->orderposting)->orderspostingDetails)->first()->price ?? null;

            $finalPrice = $price ?? ($priSale->product->dp ?? '-');

            $exportData[] = [
                'Order Number' => $priSale->order_number ?? '-',
                'Distributor Name' => $priSale->user->firstname ?? '-',
                'Distributor Code' => $priSale->user->officeid ?? '-',
                'Product Name' => $priSale->product->name ?? '-',
                'Product Model' => $priSale->product->model ?? '-',
                'Color' => $priSale->product->color ?? '-',
                'IMEI-1' => $priSale->sno ?? '-',
                'IMEI-2' => $priSale->imei ?? '-',
                'Price' => $finalPrice,
                'Date' => $priSale->created_at->format('Y-m-d')
            ];
        }
        return $this->excelService->download('Primary_report_All', $exportData);
    }

    public function primaryReportByDate(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $priSales = $this->priSaleRepo->getPrimaryByDateRange($fdate, $todate);
        $exportData = [];

        foreach ($priSales as $priSale) {
            $price = optional(optional($priSale->orderposting)->orderspostingDetails)->first()->price ?? null;

            $finalPrice = $price ?? ($priSale->product->dp ?? '-');

            $exportData[] = [
                'Order Number' => $priSale->order_number ?? '-',
                'Distributor Name' => $priSale->user->firstname ?? '-',
                'Distributor Code' => $priSale->user->officeid ?? '-',
                'Product Name' => $priSale->product->name ?? '-',
                'Product Model' => $priSale->product->model ?? '-',
                'Color' => $priSale->product->color ?? '-',
                'IMEI-1' => $priSale->sno ?? '-',
                'IMEI-2' => $priSale->imei ?? '-',
                'Price' => $finalPrice,
                'Date' => $priSale->created_at->format('Y-m-d')
            ];
        }
        return $this->excelService->download('Primary_sale_report', $exportData);
    }

    public function primaryReportByDateAndDistributor(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];
        $distributorId = $data['distributor_id'];
        $type = $data['type'];

        $jobId = (string) Str::uuid();
        $filePath = "exports/daily_sales_report_job_{$jobId}.xlsx";

        // Initialize job tracking
        $job = JobProgress::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'type' => 'daily_sales_report',
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
            'file_path' => $filePath,
        ]);

        DailySalesReport::dispatch($filePath, $jobId, $fdate, $todate, $type, $distributorId);
        return $job;

    }

    public function fullSecondaryReport()
    {
        $secSales = $this->secSaleRepo->getAllSecondaries();
        $exportData = [];

        foreach ($secSales as $secSale) {
            $exportData[] = [
                'Memo' => $secSale->memo ?? '-',
                'Distributor Name' => $secSale->user->firstname ?? '-',
                'Distributor Code' => $secSale->user->officeid ?? '-',
                'Retailer Name' => $secSale->retailer->name ?? '-',
                'Retailer Code' => $secSale->retailer->officeid ?? '-',
                'Product Name' => $secSale->product->name ?? '-',
                'Model' => $secSale->product->model ?? '-',
                'Color' => $secSale->product->color ?? '-',
                'Brand' => $secSale->brand->name ?? '-',
                'IMEI1' => $secSale->sno ?? '-',
                'IMEI2' => $secSale->imei ?? '-',
                'Date' => $secSale->created_at->format('Y-m-d'),
            ];
        }
        return $this->excelService->download('Secondary_sale_report', $exportData);
    }
    public function secondaryReportByDate(array $data)
    {
        $fdate = $data['fdate'];
        $todate = $data['todate'];

        $secSales = $this->secSaleRepo->getSecondaryByDateRange($fdate, $todate);
        $exportData = [];

        foreach ($secSales as $secSale) {
            $exportData[] = [
                'Memo' => $secSale->memo ?? '-',
                'Distributor Name' => $secSale->user->firstname ?? '-',
                'Distributor Code' => $secSale->user->officeid ?? '-',
                'Retailer Name' => $secSale->retailer->name ?? '-',
                'Retailer Code' => $secSale->retailer->officeid ?? '-',
                'Product Name' => $secSale->product->name ?? '-',
                'Model' => $secSale->product->model ?? '-',
                'Color' => $secSale->product->color ?? '-',
                'Brand' => $secSale->brand->name ?? '-',
                'IMEI1' => $secSale->sno ?? '-',
                'IMEI2' => $secSale->imei ?? '-',
                'Date' => $secSale->created_at->format('Y-m-d'),
            ];
        }
        return $this->excelService->download('Secondary_sale_report', $exportData);
    }

    public function warehouseStockReport(array $data)
    {
        $stocks = $this->stockRepo->getCurrentStocksByDate(
            $data['fdate'],
            $data['todate']
        );

        $stockData = $stocks
            ->groupBy('product_id')
            ->map(function ($stocks) {
                $product = $stocks->first()->product;

                return [
                    'Product Name' => $product->name,
                    'Product Color' => $product->color,
                    'Quantity' => $stocks->count(),
                ];
            })
            ->values();

        return $this->excelService->download('Current_stock_report', $stockData);
    }
    public function warehouseStockFull()
    {
        $stocks = $this->stockRepo->getFullCurrentStocks();

        $stockData = $stocks
            ->groupBy('product_id')
            ->map(function ($stocks) {
                $product = $stocks->first()->product;

                return [
                    'Product Name' => $product->name,
                    'Product Color' => $product->color,
                    'Quantity' => $stocks->count(),
                ];
            })
            ->values();

        return $this->excelService->download('Current_stock_report_full', $stockData);
    }

    public function imeiLifeCycleReport(UploadedFile $file)
    {
        if (!$file) {
            throw new DomainException('CSV file not found');
        }

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        // Skip header
        fgetcsv($handle);

        /* -------------------------------------------------
         | STEP 1: Collect all IMEIs first
         -------------------------------------------------*/
        $imeis = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (!empty($row[0])) {
                $imeis[] = trim($row[0]);
            }
        }

        if (empty($imeis)) {
            fclose($handle);
            throw new DomainException('No IMEI found in file');
        }

        $imeis = array_unique($imeis);

        /* -------------------------------------------------
         | STEP 2: Bulk Fetch (ONLY 4 QUERIES 🔥)
         -------------------------------------------------*/

        // Stock + Product
        $stocks = Stock::with('product')
            ->whereIn('sno', $imeis)
            ->get()
            ->keyBy('sno');

        // Primary Sale
        $priSales = Purchase::with('user')
            ->whereIn('sno', $imeis)
            ->get()
            ->keyBy('sno');

        // Secondary Sale
        $secSales = Sale::with('retailer')
            ->whereIn('sno', $imeis)
            ->get()
            ->keyBy('sno');

        // Tertiary Sale
        $terSales = Smsdetail::with('user')
            ->whereIn('sno', $imeis)
            ->get()
            ->keyBy('sno');



        /* -------------------------------------------------
         | STEP 3: Re-read CSV & Build Export
         -------------------------------------------------*/

        rewind($handle);
        fgetcsv($handle); // skip header again

        $exportData = [];

        while (($row = fgetcsv($handle)) !== false) {

            if (empty($row[0]))
                continue;

            $sno = trim($row[0]);

            $stock = isset($stocks[$sno]) ? $stocks[$sno] : null;
            $priSale = isset($priSales[$sno]) ? $priSales[$sno] : null;
            $secSale = isset($secSales[$sno]) ? $secSales[$sno] : null;
            $terSale = isset($terSales[$sno]) ? $terSales[$sno] : null;

            $remarks = $stock ? 'IMEI is available.' : 'IMEI is not available.';

            $exportData[] = [
                'IMEI' => $sno,

                'Product Model' => optional(optional($stock)->product)->model ?? '',
                'Import Date' => optional(optional($stock)->created_at)->format('d M Y, h:i A') ?? '',

                'ST1 Date' => optional(optional($priSale)->created_at)->format('d M Y, h:i A') ?? '',
                'ST1 Distributor Name' => optional(optional($priSale)->user)->firstname ?? '',
                'ST1 Distributor ID' => optional(optional($priSale)->user)->officeid ?? '',

                'ST2 Date' => optional(optional($secSale)->created_at)->format('d M Y, h:i A') ?? '',
                'ST2 Retailer Name' => optional(optional($secSale)->retail)->firstname ?? '',
                'ST2 Retailer ID' => optional(optional($secSale)->retail)->officeid ?? '',

                'SO Date' => optional(optional($terSale)->created_at)->format('d M Y, h:i A') ?? '',
                'SO Retailer Name' => optional(optional($terSale)->user)->firstname ?? '',
                'SO Retailer ID' => optional(optional($terSale)->user)->officeid ?? '',

                'Remarks' => $remarks
            ];
        }

        fclose($handle);

        /* -------------------------------------------------
         | STEP 4: Export Excel
         -------------------------------------------------*/
        return $this->excelService->download('Life_Cycle_Report', $exportData);
    }


    public function singleImeiLifeCycleReport(string $sno)
    {

        $stock = $this->stockRepo->checkStockByImeiWithProduct($sno);
        $priSale = $this->priSaleRepo->findByIMEI($sno);
        $secSale = $this->secSaleRepo->findByIMEIforCycle($sno);
        $terSale = $this->terSaleRepo->findByIMEIforCycle($sno);

        if ($stock) {
            $remarks = 'IMEI is available.';
        } else {
            $remarks = 'IMEI is not available.';
        }

        $exportData[] = [
            'IMEI' => $sno,

            'Product Model' => optional(optional($stock)->product)->model ?? '',
            'Import Date' => optional(optional($stock)->created_at)->format('d M Y, h:i A') ?? '',

            'ST1 Date' => optional(optional($priSale)->created_at)->format('d M Y, h:i A') ?? '',
            'ST1 Distributor Name' => optional(optional($priSale)->user)->firstname ?? '',
            'ST1 Distributor ID' => optional(optional($priSale)->user)->officeid ?? '',

            'ST2 Date' => optional(optional($secSale)->created_at)->format('d M Y, h:i A') ?? '',
            'ST2 Retailer Name' => optional(optional($secSale)->retail)->firstname ?? '',
            'ST2 Retailer ID' => optional(optional($secSale)->retail)->officeid ?? '',

            'SO Date' => optional(optional($terSale)->created_at)->format('d M Y, h:i A') ?? '',
            'SO Retailer Name' => optional(optional($terSale)->user)->firstname ?? '',
            'SO Retailer ID' => optional(optional($terSale)->user)->officeid ?? '',

            'Remarks' => $remarks
        ];


        return $this->excelService->download('Life_Cycle_Report', $exportData);
    }


}
