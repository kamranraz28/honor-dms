<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    protected $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function tertiarySalesReport()
    {
        return view('reports.tertiarySalesReport');
    }
    public function tertiarySalesReportDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
            'brand_id' => 'nullable',
            'sno' => 'nullable',
        ]);
        return $this->reportService->tertiarySalesReport($validated);
    }
    public function fullTertiaryReportDownload()
    {
        return $this->reportService->fullTertiaryReport();
    }
    public function currentMonthTertiaryDownload()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->tertiaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }
    public function lastSixMonthTertiaryDownload()
    {
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->tertiaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }

    public function replaceReport()
    {
        return view('reports.replaceReport');
    }
    public function replaceReportDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
        ]);
        return $this->reportService->replaceReport($validated);
    }

    public function stockReport()
    {
        return view('reports.stockReport');
    }
    public function currentMonthStockReport()
    {
        return $this->reportService->currentMonthStockReport();
    }
    public function stockReportDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
        ]);
        return $this->reportService->stockReportDownload($validated);
    }

    public function imeiStockReport()
    {
        return view('reports.imeiStockReport');
    }
    public function retailerImeiStockReportDownload()
    {
        $job = $this->reportService->retailerImeiStockReportDownload();

        return redirect()->back()->with('success', "Retailer IMEI Stock Report Generating Started on Background. (Job: JB-{$job->id}).");
    }
    public function downloadGeneratedStockReport($jobId)
    {
        return $this->reportService->downloadGeneratedStockReport($jobId);
    }
    public function distributorImeiStockReportDownload()
    {
        return $this->reportService->distributorImeiStockReportDownload();
    }
    public function distributorStockReport()
    {
        return view('reports.distributorStockReport');
    }
    public function distributorStockReportDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
            'distributor_id' => 'nullable',
        ]);
        return $this->reportService->distributorStockReportDownload($validated);
    }

    public function todaysOrder()
    {
        $fromDate = session('fromDate', date('Y-m-d'));
        $toDate = session('toDate', date('Y-m-d'));

        $todaysReport = $this->reportService->getTodaysOrderReport($fromDate, $toDate);

        return view('reports.todaysReport', compact('todaysReport', 'fromDate', 'toDate'));
    }
    public function todaysOrderStore(Request $request)
    {
        Session::put([
            'fromDate' => $request->input('fdate'),
            'toDate' => $request->input('todate'),
        ]);

        return redirect()->route('admin.todaysOrder');
    }

    public function todaysProductWiseReport()
    {
        $todaysReport = $this->reportService->getTodaysOrderReportProductWise();
        return view('reports.accounts.todaysProductWiseReport', compact('todaysReport'));
    }

    public function distributorDetailsStockReport()
    {
        return view('reports.distributorDetailsStockReport');
    }
    public function distributorDetailsStockReportDownload(Request $request)
    {
        $validated = $request->validate([
            'distributor_id' => 'nullable|integer',
            'todate' => 'required',
        ]);
        return $this->reportService->distributorDetailsStockReport($validated);
    }

    public function incompleteReport()
    {
        $mismatchedResults = $this->reportService->getIncompleteReport();
        return view('reports.incompleteReport', compact('mismatchedResults'));
    }

    public function incompleteIMEIView($id, $productId)
    {
        $report = $this->reportService->getIncompleteIMEIView($id, $productId);
        return view('reports.incompleteIMEIView', compact('report'));
    }

    public function primaryTransferReport()
    {
        return view('reports.primaryTransferReport');
    }
    public function primaryTransferReportStore(Request $request)
    {
        return $this->reportService->primaryTransferReport(
            $request->fdate,
            $request->todate
        );
    }

    public function secondaryTransferReport()
    {
        return view('reports.secondaryTransferReport');
    }

    public function secondaryTransferReportStore(Request $request)
    {
        return $this->reportService->secondaryTransfers(
            $request->fdate,
            $request->todate
        );
    }

    public function vatReport()
    {
        return view('reports.vatReport');
    }
    public function vatReportStore(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
        ]);
        return $this->reportService->vatReport($validated);
    }
    public function fullVatReportDownload()
    {
        return $this->reportService->fullVatReport();
    }
    public function currentMonthVatReportDownload()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->vatReport([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }

    public function primaryAndSecondaryDLReport()
    {
        return view('reports.primaryAndSecondaryDLReport');
    }
    public function primaryAndSecondaryDLReportDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required',
            'distributor_id' => 'nullable',
            'type' => 'required',
        ]);
        $job = $this->reportService->primaryReportByDateAndDistributor($validated);
        return redirect()->back()->with('success', "Daily Sales Report Generating Started on Background. (Job: JB-{$job->id}).");
    }
    public function fullPrimaryReportDownload()
    {
        return $this->reportService->fullPrimaryReport();
    }
    public function currentMonthPrimaryDownload()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->primaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }
    public function lastSixmonthPrimaryDownload()
    {
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->primaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }
    public function fullSecondaryReportDownload()
    {
        return $this->reportService->fullSecondaryReport();
    }
    public function currentMonthSecondaryDownload()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->secondaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }
    public function lastSixmonthSecondaryDownload()
    {
        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        return $this->reportService->secondaryReportByDate([
            'fdate' => $startDate,
            'todate' => $endDate
        ]);
    }

    public function pendingOrder()
    {
        $todaysReport = $this->reportService->getPendingOrders();
        return view('admin.pendingReport', compact('todaysReport'));
    }

    public function warehouseStockReport()
    {
        return view('reports.warehouseStockReport');
    }
    public function warehouseStockDownload(Request $request)
    {
        $validated = $request->validate([
            'fdate' => 'required',
            'todate' => 'required'
        ]);
        return $this->reportService->warehouseStockReport($validated);
    }
    public function warehouseStockFullDownload()
    {
        return $this->reportService->warehouseStockFull();
    }
    public function ImeiLifeCycleReport()
    {
        return view('reports.imeiLifeCycleReport');
    }
    public function ImeiLifeCycleReportDownload(Request $request)
    {
        return $this->reportService->imeiLifeCycleReport($request->file('csv_file'));
    }
    public function ImeiLifeCycleReportDownloadSingle(Request $request)
    {
        return $this->reportService->singleImeiLifeCycleReport($request->input('imei'));
    }
}
