<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
//use App\Http\Requests\ClassName;
//use App\Http\Controllers\AuthController;

use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;
use Rap2hpoutre\FastExcel\FastExcel;

use Redirect;
use Validator;
use Input;
use Storage;
use File;
use DB;
use Hash;


use App\User;
use App\Setting;
use App\Brand;
use App\Cat;
use App\Product;
use App\Specification;
use App\Stock;


use App\Promo;
use App\Promodetail;

use App\Smsdetail;
use App\Replace;
use App\Retailer;
use App\Sr;


use App\Purchase;
use App\Sale;
use App\Salereturn;
use App\Preturn;
use App\Transfer;


use App\Division;
use App\District;
use App\Upazila;
use App\Service;



class DistributorController extends Controller
{

    public static $code;
    public static $currency;
    public static $timezone;
    public static $contact;
    public static $vat;
    public static $semail;
    public static $favicon;
    public static $logo;


    public function __construct()
    {
        $this->middleware('auth')->except(['Test']);
        session_start();
        //return Auth::user()->level;

        //$settingCount = Setting::count();
        $settingResult = Setting::first();
        $settings = $settingResult->toArray();

        self::$code = $settings['code'];
        self::$currency = $settings['currency'];
        self::$timezone = $settings['timezone'];
        self::$vat = $settings['vat'];
        self::$contact = $settings['contact'];
        self::$semail = $settings['semail'];
        self::$favicon = $settings['favicon'];
        self::$logo = $settings['logo'];

        date_default_timezone_set(self::$timezone);
    }

    private function security()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
    }

    public function DashboardView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd(Auth::id());
        //=====================
        $returnCount = Preturn::where(['status' => 1, 'user_id' => Auth::id()])->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $_SESSION['favicon'] = self::$favicon;
        $_SESSION['logo'] = self::$logo;

        //dd($_SESSION['logo']);




        $data['totalPurchase'] = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) AS qty'))->where('user_id', Auth::id())->where('status', 1)->first();

        $data['monthlyPurchase'] = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) AS qty'))->where('status', 1)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->where('user_id', Auth::id())->first();
        $data['todayPurchase'] = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) AS qty'))->where('status', 1)->where('user_id', Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->first();




        $data['totalSale'] = Sale::select('id')->where('user_id', Auth::id())->count();
        $data['monthlySale'] = Sale::select('id')->where('user_id', Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
        $data['todaySale'] = Sale::select('id')->where('user_id', Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();

        return view('distributor.dashboards', ['data' => $data]);
    }

    //================DailySalesReport=======================


    public function DailySalesReportViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailySalesReports = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
        }




        $pdf = PDF::loadView('distributor.dailySalesReports_print', ['ssdata' => $ssdata, 'dailySalesReports' => $dailySalesReports, 'totalamount' => $totalamount]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userdailySalesReports.pdf');
    }



    public function DailySalesReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);

        $brandResult = Brand::select('id', 'name')->orderBy('id', 'desc')->get();
        $brands = $brandResult->toArray();

        $userResult = User::select('dist_return')->where('level', 100)->where('id', Auth::id())->first();
        $status = $userResult->toArray();



        $userResult = Retailer::select('id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $users = $userResult->toArray();


        $retailerResult = Retailer::select('id as id', 'name as name', 'officeid', 'retailer_id')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $retailers = $retailerResult->toArray();



        $user_id = Session::get('user_id');
        $retailer_id = Session::get('user_id');
        $sno = Session::get('sno');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');


        /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(500);
    //$sales = $saleResult->toArray();*/

        $ssdata = [];
        $totalamount = [];
        $dailySalesReports = [];

        if ($retailer_id == 'all' && $fdate && $todate && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['retailer_id'] = $retailer_id;


            $dailySalesReports = Sale::with('product', 'retailer', 'salereturn', 'sr')->select('id', 'sr_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
        } elseif ($retailer_id != 'all' && $fdate && $todate && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['retailer_id'] = $retailer_id;


            $dailySalesReports = Sale::with('product', 'retailer', 'salereturn', 'sr')->select('id', 'sr_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->where(['retailer_id' => $retailer_id])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
        } elseif ($retailer_id == 'all' && $fdate && $todate && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;

            $dailySalesReports = Sale::with('product', 'retailer', 'salereturn', 'sr')->select('id', 'sr_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->where(['sno' => $sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
        } elseif ($retailer_id != 'all' && $fdate && $todate && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['retailer_id'] = $retailer_id;


            $dailySalesReports = Sale::with('product', 'retailer', 'salereturn', 'sr')->select('id', 'sr_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->where(['retailer_id' => $retailer_id, 'sno' => $sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);

            //dd($sno);
            //dd($retailer_id);


        } else {
            $dailySalesReports = [];
        }
        //dd($dailySalesReports);

        //Session::forget(['retailer_id','user_id','sno','fdate','todate']);

        //dd(Auth::id());


        return view('distributor.dailySalesReport', ['status' => $status, 'retailers' => $retailers, 'brands' => $brands, 'users' => $users, 'ssdata' => $ssdata, 'dailySalesReports' => $dailySalesReports]);
    }


    public function DailySalesReportViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }




        Session::forget(['user_id', 'sno', 'fdate', 'todate']);

        $this->validate($request, [
            'user_id' => 'required',
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $user_id = $request->get('user_id');
        $sno = $request->get('sno');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['user_id' => $user_id, 'sno' => $sno, 'fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('distributor.dailySalesReport'));
    }

    //================DailySalesReport======================

    //================DailyCampaignReport=======================


    public function DailyCampaignReportViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailySalesReports = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
        }




        $pdf = PDF::loadView('distributor.dailyCampaignReports_print', ['ssdata' => $ssdata, 'dailySalesReports' => $dailySalesReports]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userdailySalesReports.pdf');
    }



    public function DailyCampaignReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);

        $brandResult = Brand::select('id', 'name')->orderBy('id', 'desc')->get();
        $brands = $brandResult->toArray();

        /*$userResult = User::select('id','firstname','officeid')->where('id',Auth::id())->where('level',100)->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


        $userResult = Retailer::select('retailer_id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $users = $userResult->toArray();

        $brand_id = Session::get('brand_id');
        $user_id = Session::get('user_id');
        $sno = Session::get('sno');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');



        $ssdata = [];
        $totalamount = [];
        $dailyCampaignReports = [];

        if ($brand_id == 'all' && $fdate && $todate && !$user_id && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;

            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id != 'all' && $fdate && $todate && !$user_id && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['brand_id' => $brand_id])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id == 'all' && $fdate && $todate && $user_id && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['user_id' => $user_id])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id == 'all' && $fdate && $todate && !$user_id && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['sno' => $sno])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id == 'all' && $fdate && $todate && $user_id && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['user_id' => $user_id, 'sno' => $sno])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id != 'all' && $fdate && $todate && $user_id && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['brand_id' => $brand_id, 'user_id' => $user_id])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id != 'all' && $fdate && $todate && !$user_id && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['brand_id' => $brand_id, 'sno' => $sno])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } elseif ($brand_id != 'all' && $fdate && $todate && $user_id && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['sno'] = $sno;
            $ssdata['brand_id'] = $brand_id;
            $dailyCampaignReports = Smsdetail::with('user', 'brand', 'product', 'promodetail')
                ->select(
                    'id',
                    'brand_id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'user_id',
                    'mobile',
                    'imei',
                    'sno',
                    'wperiod',
                    'remarks',
                    DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )
                ->where(['brand_id' => $brand_id, 'user_id' => $user_id, 'sno' => $sno])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->get();
        } else {
            $dailyCampaignReports = [];
        }

        //dd($dailyCampaignReports);

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);

        return view('distributor.dailyCampaignReport', ['brands' => $brands, 'users' => $users, 'ssdata' => $ssdata, 'dailyCampaignReports' => $dailyCampaignReports]);
    }


    public function DailyCampaignReportViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());



        Session::forget(['brand_id', 'user_id', 'sno', 'fdate', 'todate']);

        $this->validate($request, [
            'brand_id' => 'required',
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $brand_id = $request->get('brand_id');
        $user_id = $request->get('user_id');
        $sno = $request->get('sno');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['brand_id' => $brand_id, 'user_id' => $user_id, 'sno' => $sno, 'fdate' => $fdate, 'todate' => $todate]);
        //dd(Session::all());


        return redirect(route('distributor.dailyCampaignReport'));
    }

    //================DailySalesReport======================





    //================WcheckProduct=======================


    public function WcheckProductViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $wcheckProducts = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
        }




        $pdf = PDF::loadView('distributor.wcheckProducts_print', ['ssdata' => $ssdata, 'wcheckProducts' => $wcheckProducts, 'totalamount' => $totalamount]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userwcheckProducts.pdf');
    }



    public function WcheckProductView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        //Session::forget(['imei']);
        $ssdata = [];
        $data = [];
        $dataCount = 0;
        $imei = Session::get('imei');

        if ($imei) {
            $ssdata['imei'] = $imei;
            $dataCount = 1;

            $smsdetailCount = Smsdetail::where(['imei' => $imei])->orWhere(['sno' => $imei])->count();
            if ($smsdetailCount > 0) {

                $query = Smsdetail::with('product', 'replace', 'service')->select(
                    'id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'sno',
                    'imei',
                    'wperiod',
                    'iswar',
                    DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )

                    //->where(['user_id' => Auth::id()])
                    //->where('brand_id',2)
                    ->where(['imei' => $imei])
                    ->orWhere(['sno' => $imei])
                    //->take(1)
                    ->get();

                $data = json_decode(json_encode($query), True);

                //dd($data);

            }
        }




        //Session::forget(['user_id','fdate','todate']);

        return view('distributor.wcheckProduct', ['ssdata' => $ssdata, 'wcheckProducts' => $data, 'dataCount' => $dataCount]);
    }

    public function WcheckProductService(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'cn' => 'required',
            //'imei' => 'required',
            'mobile' => 'required',
            'service_type' => 'required',
            //'imei' => 'required',
            'problem' => 'required',
        ]);



        $id = $request->get('id');
        $smsdetail = Smsdetail::find($id);

        if ($smsdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }

        $request1['smsdetail_id'] = $request->id;
        $request1['imei'] = $smsdetail->imei;
        $request1['sno'] = $smsdetail->sno;
        $request1['brand_id'] = $smsdetail->brand_id;
        $request1['product_id'] = $smsdetail->product_id;
        $request1['contact_name'] = $request->cn;
        $request1['contact_no'] = $request->mobile;
        $request1['service_type'] = $request->service_type;
        $request1['problem'] = $request->problem;




        Service::create($request1);



        //-------------------
        // $smsdetail->imei = $request->get('imei');
        // $smsdetail->sno = $request->get('sno');
        // $smsdetail->iswar = 0;

        //  $smsdetail->save();

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }


    public function WcheckProductViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        Session::forget(['imei']);

        $this->validate($request, [
            'imei' => 'required'
        ]);


        //dd($request->all());

        $imei = $request->get('imei');

        Session::put(['imei' => $imei]);

        return redirect(route('distributor.wcheckProduct'));
    }

    //================WcheckProduct=======================





    // Distributor =======================================

    public function DistributorView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();

        //$userResult = User::with('territory')->get();
        $userResult = User::with('division', 'district', 'upazila')->where('id', Auth::id())->where('level', 100)->orderBy('id', 'desc')->paginate(100);
        //$users = $userResult->toArray();



        $divisionResult = Division::get();
        $divisions = $divisionResult->toArray();

        $districtResult = District::get();
        $districts = $districtResult->toArray();

        $upazilaResult = Upazila::get();
        $upazilas = $upazilaResult->toArray();



        //dd($users);


        return view('distributor.distributor', [
            'users' => $userResult,
            'divisions' => $divisions,
            'districts' => $districts,
            'upazilas' => $upazilas
        ]);
    }


    public function DistributorUpdate(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $user = User::find(Auth::id());

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
                'firstname' => 'required|min:2|max:50',
                //'lastname' => 'required|min:1|max:50',
                //'officeid' => 'required|unique:users',
                'contact' => 'required|numeric|min:1',
            ]);


            $image = $request->file('image');
            $nidimage = $request->file('nidimage');


            $division_id = $request->get('division_id');
            $district_id = $request->get('district_id');
            $upazila_id = $request->get('upazila_id');

            //================================================
            if (!is_null($image)) {
                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $photoimage = $image_name;
                $user->photo = $photoimage;
            }
            //================================================

            //================================================
            if (!is_null($nidimage)) {
                $this->validate($request, [
                    'nidimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['nidimage']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($nidimage->getClientOriginalName(), strripos($nidimage->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($nidimage));
                //=================================================================
                $nidimage = $image_name;
                $user->nidimage = $nidimage;
            }
            //================================================



            //=================================================================
            $user->firstname = $request->get('firstname');
            $user->lastname = $request->get('lastname');
            //$user->email = $request->get('email');
            //$user->officeid = $request->get('officeid');
            $user->contact = $request->get('contact');
            //$user->photo = $photoimage;
            //$user->nidimage = $nidimage;

            $user->division_id = $division_id;
            $user->district_id = $district_id;
            $user->upazila_id = $upazila_id;
            $user->save();

            //=================================================================



            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }


    public function UpdatePassword(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $id = $request->get('id');
        $user = User::find(Auth::id());

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                'password' => 'required|min:3|max:100',
                'confirm_password' => 'required|min:3|max:100|same:password',
            ]);

            $user->password = bcrypt($request['password']);
            $user->save();
        }

        return redirect()->back()->with('success', 'Password has been updated successfully');
    }

    public function UpdateAlternativeEmail(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $id = $request->get('id');
        $user = User::find(Auth::id());

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                'alemail' => 'required|email|min:3|max:100|unique:users'
            ]);

            $user->alemail = $request['alemail'];
            $user->save();
        }

        return redirect()->back()->with('success', 'Email has been updated successfully');
    }




    // Distributor =======================================


    public function WcheckProductReplace(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'cn' => 'required',
            //'imei' => 'required',
            'mobile' => 'required',
            'service_type' => 'required',
            //'imei' => 'required',
        ]);



        $purchases = Purchase::where('sno', $request->get('sno'))
            ->orWhere('imei', $request->get('imei'))
            ->where('user_id', Auth::user()->id) // You can add additional where clauses as needed
            ->first();

        if ($purchases == null) {
            return redirect()->back()->withErrors('This IMEI is not belongs to your stock. Please Contact with Admin.');
        }



        $id = $request->get('id');
        $smsdetail = Smsdetail::find($id);
        $oldIMEI = $smsdetail->sno;




        if ($smsdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }
        $request1['user_id'] = Auth::user()->id;
        $request1['smsdetail_id'] = $request->id;
        $request1['imei'] = $smsdetail->imei;
        $request1['sno'] = $smsdetail->sno;
        $request1['contact_name'] = $request->cn;
        $request1['contact_no'] = $request->mobile;
        $request1['service_type'] = $request->service_type;
        if (!empty($request->problem_other)) {
            $request1['problem'] = $request->problem_other;
        } else {
            $request1['problem'] = $request->problem;
        }

        $request1['brand_id'] = $smsdetail->brand_id;
        $request1['product_id'] = $smsdetail->product_id;
        $image = $request->file('image');

        if (!is_null($image)) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ]);


            $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
            Storage::put($image_name, file_get_contents($image));
        } else {
            $image_name = NULL;
        }

        $request1['memo'] = $image_name;

        // dd($request1);
        // exit();

        Replace::create($request1);





        //-------------------
        $smsdetail->imei = $request->get('imei');
        $smsdetail->sno = $request->get('sno');
        $smsdetail->iswar = 0;

        $smsdetail->save();

        Sale::where('sno', $oldIMEI)->update(['ruser_id' => 26299, 'retailer_id' => 20476]);

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }




    // Purchase =======================================

    public function PurchaseView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $productResult = Product::orderBy('id', 'desc')->get();
        $products = $productResult->toArray();

        $purchases = Purchase::with('product')->select('id', 'product_id', 'quantity', 'created_at')->where('user_id', Auth::id())->where('status', 1)->paginate(500);
        //$purchases = $purchaseResult->toArray();

        //dd($purchases);



        return view('distributor.purchase', ['purchases' => $purchases, 'products' => $products]);
    }

    public function PurchaseViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $this->validate($request, ['products' => 'required', 'quantitites' => 'required']);

        $products = $request->products;
        $quantitites = $request->quantitites;
        $data = [];
        foreach ($products as $key => $product) {


            $query = Product::select('id', 'brand_id')->where('id', $product)->take(1)->first();
            $queryresults = json_decode(json_encode($query), True);
            $brand_id = $queryresults['brand_id'];

            $data['user_id'] = Auth::id();
            $data['product_id'] = $product;
            $data['brand_id'] = $brand_id;
            $data['quantity'] = $quantitites[$key];

            Purchase::create($data);
        }

        //dd($data);


        //Cat::create($request->all());

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }


    public function PurchaseUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }



        $id = $request->get('id');
        $Purchase = Purchase::find($id);

        if ($Purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, ['product_id' => 'required', 'quantity' => 'required']);
            $Purchase->product_id = $request->get('product_id');
            $Purchase->quantity = $request->get('quantity');


            $Purchase->save();
            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }



    public function PurchaseDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Purchase = Purchase::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Purchase->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }


    // Purchase =======================================






    // Sale =======================================

    public function SaleView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $retailerlist = Retailer::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $retailerResult = $retailerlist->filter(function ($retailer) {
            return $retailer->user->active === 1;
        });
        $retailers = $retailerResult->toArray();

        $sales = Sale::with('product', 'retailer')->select('id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->paginate(25);
        //$sales = $saleResult->toArray();

        //dd(Auth::id());

        /*$purchasedatas = Purchase::select('product_id','quantity')->where('product_id',41)->where('user_id',Auth::id())->first();


$count2 = Sale::select('id')->where('product_id',41)->where('user_id',Auth::id())->count();

$tqty = $purchasedatas->quantity;
$tsqty = $count2 + 1;

dd($count2);
dd($purchasedatas);*/

        return view('distributor.sale', ['sales' => $sales, 'retailers' => $retailers]);
    }

    public function SaleViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }



        $this->validate($request, ['retailer_id' => 'required', 'snos' => 'required']);

        //dd($request->all());


        $memoNumber = DB::table('sales')->max('id') . '0';
        $retailer_id = $request->retailer_id;

        $retailerdata = Retailer::select('id', 'retailer_id')->where(['id' => $retailer_id])->first();
        $ruser_id = $retailerdata->retailer_id;

        $rtdata = User::select('id', 'district_id', 'upazila_id')->where(['id' => $ruser_id])->first();

        $dis_id = $rtdata->district_id;
        $up_id = $rtdata->upazila_id;



        $snos = array_unique($request->snos);
        $data = [];
        foreach ($snos as $key => $value) {
            $sno = $value;

            $count = Stock::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();
            $count1 = Sale::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();

            $count2 = Purchase::select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->orWhere(['imei' => $sno, 'user_id' => Auth::id()])->where('status', 1)->count();

            if ($count < 1) {
                return redirect()->back()->withErrors('S.No does not match, please try again')->withInput();
            }

            if ($count1 > 0) {
                return redirect()->back()->withErrors('Duplicate s.no does not exists, please try again')->withInput();
            }

            if ($count2 < 1) {
                return redirect()->back()->withErrors('IMEI/SNO is not available in your purchase')->withInput();
            }

            /*//=================================
      $stockdatas = Stock::select('product_id')->where('sno',$sno)->first();
      $product_id = $stockdatas->product_id;
      //==================
        $query = Product::select('id','brand_id')->where('id', $product_id)->take(1)->first();
        $queryresults = json_decode(json_encode($query), True);
        $brand_id = $queryresults['brand_id'];
      //==================


      $purchasedatas = Purchase::select('product_id',DB::raw('SUM(quantity) as quantity'))->where('product_id',$product_id)->where('user_id',Auth::id())->first();

      $tqty = $purchasedatas->quantity;

      $count2 = Sale::select('id')->where('product_id',$product_id)->where('user_id',Auth::id())->count();
      $tsqty = $count2 + 1;

      if ($tqty < $tsqty) {
        return redirect()->back()->withErrors('No more stock, please try again')->withInput();
      }

//=================================*/


            /*if ($count < 1) {
        return redirect()->back()->withErrors('S.No does not match, please try again')->withInput();
      }*/
        }


        $snoCount = 0;

        foreach ($snos as $key => $value) {
            $sno = $value;


            //=================================
            $stockdatas = Stock::select('product_id')->where('sno', $sno)->orWhere('imei', $sno)->first();
            $product_id = $stockdatas->product_id;

            $count2 = Sale::select('id')->where('product_id', $product_id)->where('user_id', Auth::id())->count();

            $purchasedatas = Purchase::select('product_id', DB::raw('SUM(quantity) as quantity'))->where('product_id', $product_id)->where('user_id', Auth::id())->where('status', 1)->first();

            $tqty = $purchasedatas->quantity;
            // dd($tqty);


            // if ($key == 3) {
            //   echo $count2 . " ==== " . $tqty;
            //   exit;
            // }




            $stock = Stock::select('id', 'sno', 'imei', 'product_id', 'brand_id')->where('sno', $snos[$key])->orWhere('imei', $snos[$key])->first();

            $data['stock_id'] = $stock->id;
            $data['memo'] = $memoNumber;
            $data['sno'] = $stock->sno;
            $data['imei'] = $stock->imei;
            $data['product_id'] = $stock->product_id;
            $data['brand_id'] = $stock->brand_id;

            $data['user_id'] = Auth::id();
            $data['retailer_id'] = $retailer_id;
            $data['ruser_id'] = $ruser_id;
            $data['dis_id'] = $dis_id;
            $data['up_id'] = $up_id;
            // dd($count2);
            Sale::create($data);

            // if ($tqty <= $count2) {
            //   //return redirect()->back()->withErrors('No more stock, please try again')->withInput();
            //   $data1[] = $stock->sno;
            //   $snoCount += 0;

            // }else{
            //   Sale::create($data);
            // }

            //=================================


        }
        // dd($snoCount);


        // if ($snoCount > 0) {
        //   $implode = implode(", ", $data1);
        //   return redirect()->back()->withErrors("$implode s.no has not been inserted, please try again")->withInput();
        // }


        //dd($data);


        //Cat::create($request->all());

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }


    public function SaleUpdate(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $Sale = Sale::find($id);

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, ['retailer_id' => 'required']);
            $Sale->retailer_id = $request->get('retailer_id');
            $Sale->save();
            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }

    public function SaleReturnUpdate(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $Sale = Sale::find($id);


        $retailerdata = Retailer::where('id', $Sale->retailer_id)->first();


        $retailer_name = $retailerdata->name;

        $rdata['sale_id'] = $Sale->id;
        $rdata['user_id'] = $Sale->user_id;
        $rdata['retailer_name'] = $retailer_name;
        $rdata['retailer_id'] = $Sale->retailer_id;
        $rdata['stock_id'] = $Sale->stock_id;
        $rdata['product_id'] = $Sale->product_id;
        $rdata['imei'] = $Sale->imei;
        $rdata['sno'] = $Sale->sno;

        //----------------------------



        //dd($request->all());

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, ['retailer_id' => 'required']);
            $Sale->retailer_id = $request->get('retailer_id');
            $Sale->save();

            Salereturn::create($rdata);

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }



    public function SaleDestroy($id)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $Sale = Sale::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Sale->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }


    // Sale =======================================




    //================DailyStockReport=======================


    public function DailyStockReportViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyStockReports = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
        }




        $pdf = PDF::loadView('distributor.dailyStockReports_print', ['ssdata' => $ssdata, 'dailyStockReports' => $dailyStockReports, 'totalamount' => $totalamount]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userdailyStockReports.pdf');
    }



    public function DailyStockReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $userCount = User::count();

        //$user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyStockReports = [];
        $ssdata['count'] = 0;


        $fdate = Session::get('fdate');
        $todate = Session::get('todate');
        $all_report = Session::get('all_report');

        if (!$fdate && !$todate && $all_report) {
            //--------------------------------------------------
            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------
            $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();


            foreach ($products as $key => $product1) {
                $product_id = $product1['id'];
                $product = $product1['name'];
                $model = $product1['model'];

                $pcount = Purchase::where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->count();

                if ($pcount > 0) {
                    $PurchaseResult = Purchase::select(DB::raw('SUM(quantity) AS sin'))->where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->groupBy('product_id')->first();
                    $Purchases = $PurchaseResult->toArray();

                    $sin = $Purchases['sin'];
                } else {
                    $sin = 0;
                }

                $scount = Sale::where('user_id', Auth::id())->where('product_id', $product_id)->count();

                if ($scount > 0) {
                    $SaleResult = Sale::select(DB::raw('COUNT(product_id) as sout'))->where('user_id', Auth::id())->where('product_id', $product_id)->groupBy('product_id')->first();
                    $Sales = $SaleResult->toArray();

                    $sout = $Sales['sout'];
                } else {
                    $sout = 0;
                }



                $dailyStockReports[] = [
                    'product_id' => $product_id,
                    'product' => $product,
                    'model' => $model,
                    'stockin' => $sin,
                    'stockout' => $sout,
                    'stock' => $sin - $sout
                ];
            }
            //--------------------------------------------------
        } elseif ($fdate && $todate && !$all_report) {
            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------

            //--------------------------------------------------

            $productResult = Product::select('id', 'name', 'model')->where('brand_id', 2)->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();


            foreach ($products as $key => $product1) {
                $product_id = $product1['id'];
                $product = $product1['name'];
                $model = $product1['model'];

                $pcount = Purchase::where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

                if ($pcount > 0) {
                    $PurchaseResult = Purchase::select(DB::raw('SUM(quantity) AS sin'))->where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                    $Purchases = $PurchaseResult->toArray();

                    $sin = $Purchases['sin'];
                } else {
                    $sin = 0;
                }

                $scount = Sale::where('user_id', Auth::id())->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

                if ($scount > 0) {
                    $SaleResult = Sale::select(DB::raw('COUNT(product_id) as sout'))->where('user_id', Auth::id())->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                    $Sales = $SaleResult->toArray();

                    $sout = $Sales['sout'];
                } else {
                    $sout = 0;
                }



                $dailyStockReports[] = [
                    'product_id' => $product_id,
                    'product' => $product,
                    'model' => $model,
                    'stockin' => $sin,
                    'stockout' => $sout,
                    'stock' => $sin - $sout
                ];
            }
        }


        //Session::forget(['user_id','fdate','todate']);

        return view('distributor.dailyStockReport', ['ssdata' => $ssdata, 'dailyStockReports' => $dailyStockReports]);
    }


    public function DailyStockReportViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate', 'all_report']);


        if ($request->get('all_report') == null) {

            $this->validate($request, [
                'fdate' => 'required',
                'todate' => 'required',
            ]);


            $fdate = $request->get('fdate');
            $todate = $request->get('todate');
            //$all_report = $request->get('all_report');

            Session::put(['fdate' => $fdate, 'todate' => $todate]);
        } else {
            $this->validate($request, [
                'all_report' => 'required'
            ]);
            $all_report = $request->get('all_report');
            Session::put(['all_report' => $all_report]);
        }

        return redirect(route('distributor.dailyStockReport'));
    }

    //================DailyStockReport=======================



    //================DailyPurchaseReport=======================

    public function DailyPurchaseReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $userCount = User::count();

        //$user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyPurchaseReports = [];
        $purchases = [];
        $ssdata['count'] = 0;


        $fdate = Session::get('fdate');
        $todate = Session::get('todate');
        $all_report = Session::get('all_report');

        if ($fdate && $todate && !$all_report) {
            //--------------------------------------------------
            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------

            $purchases = Purchase::with('product', 'user')->select('id', 'user_id', 'product_id', 'quantity', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->where('status', 1)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
        } elseif (!$fdate && !$todate && $all_report) {

            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------

            $purchases = Purchase::with('product', 'user')->select('id', 'user_id', 'product_id', 'quantity', 'sno', 'imei', 'created_at')->where('user_id', Auth::id())->where('status', 1)->paginate(500);
        }
        //--------------------------------------------------
        //dd(Session::all());
        //dd($purchases);
        //Session::forget(['user_id','fdate','todate']);

        return view('distributor.dailyPurchaseReport', ['ssdata' => $ssdata, 'dailyPurchaseReports' => $dailyPurchaseReports, 'purchases' => $purchases]);
    }


    public function DailyPurchaseReportViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate', 'all_report']);


        if ($request->get('all_report') == null) {

            $this->validate($request, [
                'fdate' => 'required',
                'todate' => 'required',
            ]);


            $fdate = $request->get('fdate');
            $todate = $request->get('todate');
            //$all_report = $request->get('all_report');

            Session::put(['fdate' => $fdate, 'todate' => $todate]);
        } else {
            $this->validate($request, [
                'all_report' => 'required'
            ]);
            $all_report = $request->get('all_report');
            Session::put(['all_report' => $all_report]);
        }

        return redirect(route('distributor.dailyPurchaseReport'));
    }

    //================DailyPurchaseReport=======================






    //================DailyPurchaseApprove=======================

    public function DailyPurchaseApproveView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $userCount = User::count();

        //$user_id = Session::get('user_id');
        /*$fdate = Session::get('fdate');
    $todate = Session::get('todate');

    $ssdata = [];
    $totalamount = [];
    $dailyPurchaseApproves = [];
    $purchases = [];
    $ssdata['count'] = 0;


    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------

    $purchases = Purchase::with('product','user')->select('id','user_id','product_id','quantity','sno','imei','created_at','status')->where('user_id',Auth::id())->where('status',0)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->OrderBy('id','DESC')->take(50)->get();*/
        //--------------------------------------------------
        //dd($fdate);
        //dd($purchases);
        //Session::forget(['user_id','fdate','todate']);

        $dailyPurchaseApproves = [];
        $purchases = [];
        $ssdata['count'] = 0;

        $purchases = Purchase::with('product', 'user')->select('id', 'user_id', 'product_id', 'quantity', 'sno', 'imei', 'created_at', 'status')->where('user_id', Auth::id())->where('status', 0)->OrderBy('id', 'DESC')->get();


        return view('distributor.dailyPurchaseApprove', ['ssdata' => $ssdata, 'dailyPurchaseApproves' => $dailyPurchaseApproves, 'purchases' => $purchases]);
    }


    public function DailyPurchaseApproveViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate']);


        $this->validate($request, [
            'fdate' => 'required',
            'todate' => 'required',
        ]);


        $fdate = $request->get('fdate');
        $todate = $request->get('todate');
        Session::put(['fdate' => $fdate, 'todate' => $todate]);
        return redirect(route('distributor.dailyPurchaseApprove'));
    }

    public function DailyPurchaseApproveViewStatus(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $user_id = $request->get('user_id');
        $childItem = $request->get('childItem');


        if (!$childItem) {
            return redirect()->back()->withErrors('There are no data found, please try again ...');
        }

        foreach ($childItem as $key => $value) {
            $id = $value;

            $rowCount = Purchase::where(['id' => $id, 'user_id' => Auth::id()])->count();

            if ($rowCount > 0) {
                DB::table('purchases')->where(['id' => $id])->update(['status' => 1]);
            } else {
                return redirect()->back()->withErrors('There are no data with this id');
            }
        }

        return redirect()->back()->with('success', 'Data has been updated successfully');
    }

    //================DailyPurchaseApprove=======================



    //=========================ajaxcode======================

    public function varifyserialno($sno)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $count = Stock::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();
        $count1 = Sale::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();

        $count2 = Purchase::select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->orWhere(['imei' => $sno, 'user_id' => Auth::id()])->where('status', 1)->count();

        if ($count < 1) {
            return 0;
        } elseif ($count1 > 0) {
            return 1;
        } elseif ($count2 < 1) {
            return 2;
        } else {
            $stockdatas = Stock::with('product')->select('product_id')->where('sno', $sno)->orWhere('imei', $sno)->first();
            $product_id = $stockdatas->product_id;

            $product = $stockdatas->product['name'];
            $model = $stockdatas->product['model'];

            return $product . "-" . $model;
        }
    }



    public function varifyserialnoTwo($id, $no)
    {
        //if (Auth::user()->level != 100) { return redirect()->route('logout');}

        $countt = Preturn::select('id')->where(['sno' => $no, 'ruser_id' => $id])->count();
        $count = Sale::select('id')->where(['sno' => $no, 'ruser_id' => $id])->count();
        $count1 = Smsdetail::select('id')->where(['sno' => $no, 'user_id' => $id])->count();
        $count2 = Stock::select('id')->where(['sno' => $no])->count();

        if ($count == 0 || $countt > 0) {
            return 0;
        } else if ($count1 > 0) {
            return 1;
        } else if ($count2 == 0) {
            return 2;
        } else {
            return 3;
        }
    }

    //=========================ajaxcode======================



    //================DailyRetailerStockReportForDistrict=======================



    public function DailyRetailerStockReportForRetailerView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);



        $retailerResult = Retailer::select('id', 'name', 'officeid', 'retailer_id', 'user_id')->where(['user_id' => Auth::id()])->orderBy('id', 'desc')->get();
        $retailers = $retailerResult->toArray();
        //dd($retailers);


        $user_id = Session::get('user_id');
        $sno = Session::get('sno');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');



        $ssdata = [];
        $totalamount = [];
        $dailyRetailerStockReports = [];


        if ($user_id) {
            # code...
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;

            $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();

            foreach ($products as $key => $productValue) {
                $product_id = $productValue['id'];
                $product = $productValue['name'];
                $model = $productValue['model'];

                //========================
                if ($user_id == 'all') {
                    $userResult = Retailer::select('retailer_id')->where(['user_id' => Auth::id()])->get();
                    $usersdatas[] = $userResult->toArray();


                    foreach ($usersdatas as $key => $uservalue) {
                        //$user_id = $value['id'];
                        //===============
                        // with retailer_id all========

                        @$retailer_id = $uservalue['retailer_id'];

                        $pcount = Sale::where('product_id', $product_id)->where('ruser_id', $retailer_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

                        if ($pcount > 0) {
                            $SaleResult = Sale::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('ruser_id', $retailer_id)->where('product_id', $product_id)->where('ruser_id', $retailer_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                            $Sales = $SaleResult->toArray();
                            //$retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
                            $retailer = "All Retailers";
                            $sin = $Sales['sin'];
                        } else {
                            $retailer = "All Retailers";
                            $sin = 0;
                        }

                        $scount = Smsdetail::where('product_id', $product_id)->where('user_id', $retailer_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

                        if ($scount > 0) {
                            $SmsdetailResult = Smsdetail::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('product_id', $product_id)->where('user_id', $retailer_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                            $Smsdetails = $SmsdetailResult->toArray();
                            //$retailer = $Smsdetails['user']['firstname'] . " - ".
                            //$retailer = "All Retailers";
                            $sout = $Smsdetails['sout'];
                        } else {
                            // $retailer = "All Retailers";
                            $sout = 0;
                        }

                        // with retailer_id all========
                        //===============

                        $dailyRetailerStockReports[] = [
                            'retailer' => $retailer,
                            'product_id' => $product_id,
                            'product' => $product,
                            'model' => $model,
                            'stockin' => $sin,
                            'stockout' => $sout,
                            'stock' => $sin - $sout
                        ];
                    }
                } else {

                    // with user_id========

                    $pcount = Sale::where('ruser_id', $user_id)->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();


                    if ($pcount > 0) {
                        $SaleResult = Sale::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('ruser_id', $user_id)->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                        $Sales = $SaleResult->toArray();
                        $retailer = $Sales['user']['firstname'] . " - " . $Sales['user']['officeid'];
                        $sin = $Sales['sin'];
                    } else {
                        //$retailer = " - ";
                        $sin = 0;

                        $userData = User::where('id', $user_id)->first();
                        $retailer = $userData->firstname . " " . $userData->officeid;
                    }

                    $scount = Smsdetail::where('user_id', $user_id)->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

                    if ($scount > 0) {
                        $SmsdetailResult = Smsdetail::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('user_id', $user_id)->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                        $Smsdetails = $SmsdetailResult->toArray();
                        $retailer = $Smsdetails['user']['firstname'] . " - " . $Smsdetails['user']['officeid'];
                        $sout = $Smsdetails['sout'];
                    } else {
                        //$retailer = " - ";
                        $sout = 0;
                        $userData = User::where('id', $user_id)->first();
                        $retailer = $userData->firstname . " " . $userData->officeid;
                    }

                    // with user_id========

                    //===============

                    $dailyRetailerStockReports[] = [
                        'retailer' => $retailer,
                        'product_id' => $product_id,
                        'product' => $product,
                        'model' => $model,
                        'stockin' => $sin,
                        'stockout' => $sout,
                        'stock' => $sin - $sout
                    ];
                }
                //==============================
            }
        }


        //dd($dailyRetailerStockReports);

        //Session::forget(['upazila_id','brand_id','user_id','sno','fdate','todate']);

        return view('distributor.dailyRetailerStockReportForRetailer', [
            'retailers' => $retailers,
            'ssdata' => $ssdata,
            'dailyRetailerStockReports' => $dailyRetailerStockReports
        ]);
    }


    public function DailyRetailerStockReportForRetailerViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());


        Session::forget(['user_id', 'fdate', 'todate']);

        $this->validate($request, [
            'user_id' => 'required',
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $user_id = $request->get('user_id');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('distributor.dailyRetailerStockReportForRetailer'));
    }

    //================DailyRetailerStockReportForDistrict======================



    # ReportV1====

    //================DailyPurchaseReportV1=======================

    public function DailyPurchaseReportV1View()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $userCount = User::count();

        //$user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyPurchaseReportV1s = [];
        $purchases = [];
        $ssdata['count'] = 0;


        $fdate = Session::get('fdate');
        $todate = Session::get('todate');
        $all_report = Session::get('all_report');

        if ($fdate && $todate && !$all_report) {
            //--------------------------------------------------
            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------

            $query = Product::select('name', 'id', 'model')->get();
            $products = $query->toArray();

            foreach ($products as $key => $product) {
                $product_id = $product['id'];
                $productname = $product['name'];
                $productmodel = $product['model'];

                $query = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                    ->where('user_id', Auth::id())
                    ->where(['product_id' => $product_id, 'status' => 1])
                    ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                    ->first();
                $purchase = $query->toArray();

                $purchases[] = [
                    'product' => $productname,
                    'model' => $productmodel,
                    'quantity' => $purchase['quantity'],
                ];
            }

            //--------------------------------------------------

        } elseif (!$fdate && !$todate && $all_report) {

            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------
            //--------------------------------------------------

            $query = Product::select('name', 'id', 'model')->get();
            $products = $query->toArray();



            foreach ($products as $key => $product) {
                $product_id = $product['id'];
                $productname = $product['name'];
                $productmodel = $product['model'];

                //dd($productmodel);


                $query = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                    ->where('user_id', Auth::id())
                    ->where(['product_id' => $product_id, 'status' => 1])
                    ->first();
                $purchase = $query->toArray();

                $purchases[] = [
                    'product' => $productname,
                    'model' => $productmodel,
                    'quantity' => $purchase['quantity'],
                ];
            }

            //--------------------------------------------------
        }
        //--------------------------------------------------

        return view('distributor.dailyPurchaseReportV1', ['ssdata' => $ssdata, 'dailyPurchaseReportV1s' => $dailyPurchaseReportV1s, 'purchases' => $purchases]);
    }


    public function DailyPurchaseReportV1ViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate', 'all_report']);


        if ($request->get('all_report') == null) {

            $this->validate($request, [
                'fdate' => 'required',
                'todate' => 'required',
            ]);


            $fdate = $request->get('fdate');
            $todate = $request->get('todate');
            //$all_report = $request->get('all_report');

            Session::put(['fdate' => $fdate, 'todate' => $todate]);
        } else {
            $this->validate($request, [
                'all_report' => 'required'
            ]);
            $all_report = $request->get('all_report');
            Session::put(['all_report' => $all_report]);
        }

        return redirect(route('distributor.dailyPurchaseReportV1'));
    }

    //================DailyPurchaseReportV1=======================



    //================DailySalesReportV1=======================

    public function DailySalesReportV1View()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        $userResult = Retailer::select('id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $users = $userResult->toArray();

        $query = Product::select('name', 'id', 'model')->get();
        $products = $query->toArray();




        $user_id = Session::get('user_id');
        $retailer_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');


        /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(500);
    //$sales = $saleResult->toArray();*/

        $ssdata = [];
        $totalamount = [];
        $dailySalesReportV1s = [];

        if ($retailer_id == 'all' && $fdate && $todate) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['retailer_id'] = $retailer_id;
            //----------------------
            $query = Retailer::select('id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->get();
            $retailers = $query->toArray();

            $array_qty = [];
            foreach ($retailers as $key => $retailer) {

                $retailer_id = $retailer['id'];

                $query = Product::select('name', 'id', 'model')->get();
                $products = $query->toArray();

                foreach ($products as $key => $product) {
                    $product_id = $product['id'];


                    $count = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['retailer_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['retailer_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $sales = $query->toArray();

                        $data1[$key] = $sales['quantity'];
                    } else {
                        $data1[$key] = 0;
                    }
                }

                $dailySalesReportV1s[] = [
                    'retailname' => $retailer['firstname'],
                    'retailcode' => $retailer['officeid'],
                    'quantity' => $data1,
                    'total' => array_sum($data1),
                ];
            }


            //dd($dailySalesReportV1s);

            //----------------------

        } elseif ($retailer_id != 'all' && $fdate && $todate) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['retailer_id'] = $retailer_id;
            //----------------------
            $query = Retailer::select('id as id', 'name as firstname', 'officeid')->where('id', $retailer_id)->get();
            $retailers = $query->toArray();

            $array_qty = [];
            foreach ($retailers as $key => $retailer) {

                $retailer_id = $retailer['id'];

                $query = Product::select('name', 'id', 'model')->get();
                $products = $query->toArray();

                foreach ($products as $key => $product) {
                    $product_id = $product['id'];


                    $count = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['retailer_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['retailer_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $sales = $query->toArray();

                        $data1[$key] = $sales['quantity'];
                    } else {
                        $data1[$key] = 0;
                    }
                }

                $dailySalesReportV1s[] = [
                    'retailname' => $retailer['firstname'],
                    'retailcode' => $retailer['officeid'],
                    'quantity' => $data1,
                    'total' => array_sum($data1),
                ];
            }


            //dd($dailySalesReportV1s);

            //----------------------

        } else {
            $dailySalesReportV1s = [];
        }

        //dd($dailySalesReportV1s);

        //Session::forget(['retailer_id','user_id','sno','fdate','todate']);

        //dd(Auth::id());


        return view('distributor.dailySalesReportV1', ['users' => $users, 'products' => $products, 'ssdata' => $ssdata, 'dailySalesReportV1s' => $dailySalesReportV1s]);
    }


    public function DailySalesReportV1ViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }




        Session::forget(['user_id', 'sno', 'fdate', 'todate']);

        $this->validate($request, [
            'user_id' => 'required',
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $user_id = $request->get('user_id');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('distributor.dailySalesReportV1'));
    }

    //================DailySalesReportV1======================





    //================DailyStockReportV1=======================

    public function DailyStockReportV1View()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $userCount = User::count();

        //$user_id = Session::get('user_id');
        //$fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyStockReportV1s = [];
        $ssdata['count'] = 0;


        $fdate = '2019-01-01';


        $todate = Session::get('todate');
        $all_report = Session::get('all_report');

        //--------------------------------------------------
        $ssdata['count'] = 1;
        $ssdata['fdate'] = '2019-01-01';
        $ssdata['todate'] = $todate;
        //--------------------------------------------------

        //--------------------------------------------------

        $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
        $products = $productResult->toArray();


        foreach ($products as $key => $product1) {
            $product_id = $product1['id'];
            $product = $product1['name'];
            $model = $product1['model'];

            $pcount = Purchase::where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

            if ($pcount > 0) {
                $PurchaseResult = Purchase::select(DB::raw('SUM(quantity) AS sin'))->where('user_id', Auth::id())->where('product_id', $product_id)->where('status', 1)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                $Purchases = $PurchaseResult->toArray();

                $sin = $Purchases['sin'];
            } else {
                $sin = 0;
            }

            $scount = Sale::where('user_id', Auth::id())->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

            if ($scount > 0) {
                $SaleResult = Sale::select(DB::raw('COUNT(product_id) as sout'))->where('user_id', Auth::id())->where('product_id', $product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
                $Sales = $SaleResult->toArray();

                $sout = $Sales['sout'];
            } else {
                $sout = 0;
            }



            $dailyStockReportV1s[] = [
                'product_id' => $product_id,
                'product' => $product,
                'model' => $model,
                'stockin' => $sin,
                'stockout' => $sout,
                'stock' => $sin - $sout
            ];
        }




        //Session::forget(['user_id','fdate','todate']);

        return view('distributor.dailyStockReportV1', ['ssdata' => $ssdata, 'dailyStockReportV1s' => $dailyStockReportV1s]);
    }


    public function DailyStockReportV1ViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        Session::forget(['todate']);
        $this->validate($request, [
            //'fdate' => 'required',
            'todate' => 'required',
        ]);

        $todate = $request->get('todate');

        Session::put(['todate' => $todate]);

        return redirect(route('distributor.dailyStockReportV1'));
    }

    //================DailyStockReportV1=======================



    //================DailyRtlStockReportV1=======================

    public function DailyRtlStockReportV1View()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        $userResult = Retailer::select('retailer_id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $users = $userResult->toArray();

        $query = Product::select('name', 'id', 'model')->get();
        $products = $query->toArray();




        $user_id = Session::get('user_id');
        $retailer_id = Session::get('user_id');
        $fdate = '2019-01-01';
        $todate = Session::get('todate');


        /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(500);
    //$sales = $saleResult->toArray();*/

        $ssdata = [];
        $totalamount = [];
        $dailyRtlStockReportV1s = [];

        if ($retailer_id == 'all' && $fdate && $todate) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['retailer_id'] = $retailer_id;
            //----------------------
            $query = Retailer::select('retailer_id as id', 'name as firstname', 'officeid')->where('user_id', Auth::id())->get();
            $retailers = $query->toArray();

            $array_qty = [];
            foreach ($retailers as $key => $retailer) {

                $retailer_id = $retailer['id'];

                $query = Product::select('name', 'id', 'model')->get();
                $products = $query->toArray();

                foreach ($products as $key => $product) {
                    $product_id = $product['id'];

                    # sales =====
                    $count = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['ruser_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['ruser_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $sales = $query->toArray();

                        $data1[$key] = $sales['quantity'];
                    } else {
                        $data1[$key] = 0;
                    }
                    # sales =====
                    # smsdetails =====
                    $count = Smsdetail::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['user_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Smsdetail::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['user_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $smsdetails = $query->toArray();

                        $data2[$key] = $smsdetails['quantity'];
                    } else {
                        $data2[$key] = 0;
                    }
                    # smsdetails =====

                }

                $dailyRtlStockReportV1s[] = [
                    'retailname' => $retailer['firstname'],
                    'retailcode' => $retailer['officeid'],
                    'data1' => $data1,
                    'data2' => $data2,
                    'total1' => array_sum($data1),
                    'total2' => array_sum($data2),
                ];
            }


            //dd($dailyRtlStockReportV1s);

            //----------------------

        } elseif ($retailer_id != 'all' && $fdate && $todate) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['user_id'] = $user_id;
            $ssdata['retailer_id'] = $retailer_id;
            //----------------------
            $query = Retailer::select('retailer_id as id', 'name as firstname', 'officeid')->where('retailer_id', $retailer_id)->get();
            $retailers = $query->toArray();

            $array_qty = [];
            foreach ($retailers as $key => $retailer) {

                $retailer_id = $retailer['id'];

                $query = Product::select('name', 'id', 'model')->get();
                $products = $query->toArray();

                foreach ($products as $key => $product) {
                    $product_id = $product['id'];


                    # sales =====
                    $count = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['ruser_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['ruser_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $sales = $query->toArray();

                        $data1[$key] = $sales['quantity'];
                    } else {
                        $data1[$key] = 0;
                    }


                    # sales =====
                    # smsdetails =====
                    $count = Smsdetail::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                        ->where(['user_id' => $retailer_id, 'product_id' => $product_id])
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                        ->groupBy('product_id')
                        ->count();

                    if ($count > 0) {
                        $query = Smsdetail::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
                            ->where(['user_id' => $retailer_id, 'product_id' => $product_id])
                            ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                            ->groupBy('product_id')
                            ->first();
                        $smsdetails = $query->toArray();

                        $data2[$key] = $smsdetails['quantity'];
                    } else {
                        $data2[$key] = 0;
                    }
                    # smsdetails =====
                }

                $dailyRtlStockReportV1s[] = [
                    'retailname' => $retailer['firstname'],
                    'retailcode' => $retailer['officeid'],
                    'data1' => $data1,
                    'data2' => $data2,
                    'total1' => array_sum($data1),
                    'total2' => array_sum($data2),
                ];
            }


            //dd($dailyRtlStockReportV1s);

            //----------------------

        } else {
            $dailyRtlStockReportV1s = [];
        }

        //dd($dailyRtlStockReportV1s);

        //Session::forget(['retailer_id','user_id','sno','fdate','todate']);

        //dd(Auth::id());


        return view('distributor.dailyRtlStockReportV1', ['users' => $users, 'products' => $products, 'ssdata' => $ssdata, 'dailyRtlStockReportV1s' => $dailyRtlStockReportV1s]);
    }


    public function DailyRtlStockReportV1ViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        Session::forget(['user_id', 'todate']);

        $this->validate($request, [
            'user_id' => 'required',
            'todate' => 'required'
        ]);

        $todate = $request->get('todate');
        $user_id = $request->get('user_id');

        Session::put(['user_id' => $user_id, 'todate' => $todate]);
        return redirect(route('distributor.dailyRtlStockReportV1'));
    }

    //================DailyRtlStockReportV1======================


    # ReportV1====







    // ReturnProduct =======================================

    public function ReturnProductView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //=====================
        $returnCount = Preturn::where(['status' => 1, 'user_id' => Auth::id()])->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $preturns = Preturn::with('product')
            ->select('id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at', 'updated_at', 'status', DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))
            ->where(['user_id' => Auth::id()])
            ->whereIn('status', [1, 4]) // Filter by both status 1 and 4
            ->orderBy('status', 'ASC')
            ->paginate(200);

        return view('distributor.returnProduct', ['preturns' => $preturns]);
    }




    public function SReturnProductView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //=====================
        $returnCount = Preturn::where(['status' => 2, 'user_id' => Auth::id()])->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $preturns = Preturn::with('product')
            ->select('id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at', 'updated_at', 'status', DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))
            ->where(['user_id' => Auth::id()])
            ->whereIn('status', [2, 3]) // Filter by both status 1 and 4
            ->orderBy('status', 'ASC')
            ->paginate(200);

        return view('distributor.sreturnProduct', ['preturns' => $preturns]);
    }



    public function ReturnProductViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $this->validate($request, ['snos' => 'required']);
        //dd($request->all());
        $snos = array_unique($request->snos);
        $data = [];
        $snoCount = 0;
        foreach ($snos as $key => $value) {
            $sno = $value;
            //=================================
            $count = Preturn::where(['sno' => $sno, 'status' => 1, 'user_id' => Auth::id()])->count();

            if ($count > 0) {
                DB::table('preturns')->where(['sno' => $sno, 'user_id' => Auth::id()])->update(['status' => 4]);
                DB::table('sales')->where(['sno' => $sno])->delete();
            } else {
                $data[] = $sno;
                $snoCount += 1;
            }
            //=================================

        }

        if ($snoCount > 0) {
            $implode = implode(", ", $data);
            return redirect()->back()->withErrors("$implode s.no has not been update, please check status , others sno has been updated")->withInput();
        }
        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }


    public function SReturnProductViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $this->validate($request, ['snos' => 'required']);

        $snos = array_unique($request->snos);
        $data = [];

        foreach ($snos as $key => $value) {
            $sno = $value;

            $count = Sale::select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->count();
            $count1 = Smsdetail::select('id')->where(['sno' => $sno])->count();
            $count2 = Stock::select('id')->where(['sno' => $sno])->count();

            $count3 = Preturn::select('id')->where(['sno' => $sno, 'status' => '2'])->count();
            $count4 = purchase::select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->count();

            if ($count > 0) {
                return redirect()->back()->withErrors('This s.no is not allowed for returning.First do ST2 Return')->withInput();
            } else if ($count1 > 0) {
                return redirect()->back()->withErrors('This s.no has all ready been sold')->withInput();
            } else if ($count2 == 0) {
                return redirect()->back()->withErrors('This s.no does not match')->withInput();
            } else if ($count3 > 0) {
                return redirect()->back()->withErrors('This s.no has already in return process')->withInput();
            } else if ($count4 == 0) {
                return redirect()->back()->withErrors('This IMEI/SNO not available in ST1 Database')->withInput();
            }
        }


        foreach ($snos as $key => $value) {
            $sno = $value;
            //=================================
            $saleResult = purchase::where(['sno' => $sno, 'user_id' => Auth::id()])->first();
            $sales = $saleResult->toArray();
            $sales['status'] = 2;

            //=================================
            Preturn::create($sales);
        }


        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }



    public function ReturnProductDelete($id = null)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $Preturn = Preturn::find($id);

        $status = $Preturn->status;



        if ($Preturn === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($status > 1) {
                return redirect()->back()->withErrors('This item can not be deleted because of distributor approved this item');
            } else {
                $Preturn->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }

    public function SReturnProductDelete($id = null)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $Preturn = Preturn::find($id);

        $status = $Preturn->status;



        if ($Preturn === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($status > 3) {
                return redirect()->back()->withErrors('This item can not be deleted because of ND approved this IMEI/SNO');
            } else {
                $Preturn->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }




    // ReturnProduct =======================================


    // ReturndProduct =======================================

    public function ReturndProductView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $retailerResult = Retailer::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        $retailers = $retailerResult->toArray();

        //=====================
        $returnCount = Transfer::where(['status' => 4, 'user_id' => Auth::id()])->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $preturns = Transfer::with('product')->select('id', 'product_id', 'new_retailer_id', 'retailer_id', 'sno', 'imei', 'sale_date', 'created_at', 'updated_at', 'status', DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))->where(['status' => 4, 'user_id' => Auth::id()])->OrderBy('status', 'ASC')->paginate(200);
        //$sales = $saleResult->toArray();

        return view('distributor.returndProduct', ['preturns' => $preturns, 'retailers' => $retailers]);
    }

    // public function ReturndProductViewStore(Request $request){
    //   if (Auth::user()->level != 100) { return redirect()->route('logout');}

    //   $this->validate($request,['snos'=>'required','retailer_id'=>'required']);

    //   //dd($request->all());

    //   $retailer_id = $request->retailer_id;

    //   $snos =array_unique($request->snos);
    //   $data = [];

    //   foreach ($snos as $key => $value) {
    //       $sno = $value;

    //       $count = Sale::select('id')->where(['sno'=>$sno,'ruser_id'=>$retailer_id])->count();
    //       $count1 = Smsdetail::select('id')->where(['sno'=>$sno,'user_id'=>$retailer_id])->count();
    //       $count2 = Stock::select('id')->where(['sno'=>$sno])->count();

    //     $count3 = Preturn::select('id')->where(['sno'=>$sno,'status'=>'1'])->orWhere([])->count();


    //      if ($count == 0) {
    //       return redirect()->back()->withErrors('This s.no is not allowed for returning')->withInput();
    //      }else if ($count1 > 0) {
    //       return redirect()->back()->withErrors('This s.no has all ready been sold')->withInput();
    //      }else if ($count2 == 0){
    //       return redirect()->back()->withErrors('This s.no does not match')->withInput();
    //      }else if ($count3 > 0 ){
    //       return redirect()->back()->withErrors('This s.no has already in return process')->withInput();
    //      }

    //   }


    //   foreach ($snos as $key => $value) {
    //     $sno = $value;
    //   //=================================
    //     $saleResult = Sale::where(['sno'=>$sno,'ruser_id'=>$retailer_id])->first();
    //     $sales = $saleResult->toArray();

    //   //=================================
    //     $sales['status'] = 4;
    //     DB::table('sales')->where(['sno'=>$sno])->delete();

    //     Preturn::create($sales);

    //   }


    //   return redirect()->back()->with('success', 'Data has been inserted successfully');


    // }


    public function ReturndProductViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $this->validate($request, ['snos' => 'required', 'retailer_id' => 'required']);

        //dd($request->all());
        $user_id = Auth::id();
        // dd($user_id);

        $retailer_id = $request->retailer_id;
        $ret_id = Retailer::where('retailer_id', $retailer_id)->value('id');
        // dd($ret_id);


        $snos = array_unique($request->snos);
        $data = [];

        foreach ($snos as $key => $value) {
            $sno = $value;

            $count = Sale::select('id')->where(['sno' => $sno, 'user_id' => $user_id])->count();
            $count1 = Smsdetail::select('id')->where(['sno' => $sno])->count();
            $count2 = Stock::select('id')->where(['sno' => $sno])->count();

            $count3 = Preturn::select('id')->where(['sno' => $sno, 'status' => '1'])->orWhere([])->count();


            if ($count == 0) {
                return redirect()->back()->withErrors('This s.no is not allowed for returning')->withInput();
            } else if ($count1 > 0) {
                return redirect()->back()->withErrors('This s.no has all ready been sold')->withInput();
            } else if ($count2 == 0) {
                return redirect()->back()->withErrors('This s.no does not match')->withInput();
            } else if ($count3 > 0) {
                return redirect()->back()->withErrors('This s.no has already in return process')->withInput();
            }
        }


        foreach ($snos as $key => $value) {
            $sno = $value;
            //=================================
            $saleResult = Sale::where(['sno' => $sno])->first();
            // dd($saleResult);
            $sale_date = $saleResult->created_at;

            $sales = $saleResult->toArray();


            //=================================
            $sales['status'] = 4;
            DB::table('sales')->where(['sno' => $sno])->update(['ruser_id' => $retailer_id, 'retailer_id' => $ret_id]);

            Transfer::create($sales);
            DB::table('transfers')->where('sno', $sno)->update(['new_retailer_id' => $retailer_id, 'sale_date' => $sale_date]);
        }


        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }

    public function ReturndProductDelete($id = null)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $Preturn = Preturn::find($id);

        $status = $Preturn->status;
        if ($Preturn === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($status > 1) {
                return redirect()->back()->withErrors('This item can not be deleted becouse of distributor approved this item');
            } else {
                $Preturn->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }



    // ReturndProduct =======================================


    // Retailer =======================================

    public function RetailerView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();


        //$userResult = User::with('territory')->get();
        $userResult = User::with('division', 'district', 'upazila')->where(['tso_id' => Auth::id(), 'level' => 200])->orderBy('id', 'desc')->paginate(300);
        //$users = $userResult->toArray();

        //dd($userResult);
        //

        $divisionResult = Division::get();
        $divisions = $divisionResult->toArray();

        $districtResult = District::get();
        $districts = $districtResult->toArray();

        $upazilaResult = Upazila::get();
        $upazilas = $upazilaResult->toArray();

        return view('distributor.retailer', ['retailers' => $userResult, 'divisions' => $divisions, 'districts' => $districts, 'upazilas' => $upazilas]);
    }

    public function RetailerViewStore(Request $request)
    {

        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $statement = DB::select("show table status like 'users'");
        $ainid = $statement[0]->Auto_increment;



        //dd($request->all());

        //========================================================================================
        $this->validate($request, [
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            'firstname' => 'required|min:2|max:50',
            'contact_name' => 'required|min:2|max:50',
            'market_name' => 'required|min:2|max:50',
            'store_type' => 'required|min:2|max:50',
            //'lastname' => 'required|min:1|max:50',
            //'email' => 'required|email|unique:users',
            //'officeid' => 'required|unique:users',
            //'password' => 'required|min:3|max:20',
            //'confirm_password' => 'required|min:3|max:20|same:password',
            'contact' => 'required|numeric|min:1',
            'address' => 'required|min:2|max:99',
            //'level' => 'required'
        ]);


        $officeid = "SX" . strtoupper(substr(uniqid(), -4)) . date("s");

        $image = $request->file('image');

        if (!is_null($image)) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]);


            $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
            Storage::put($image_name, file_get_contents($image));
        } else {
            $image_name = NULL;
        }

        $request['remember_token'] = $request['_token'];
        $request['officeid'] = $officeid;

        $request['password'] = bcrypt($officeid);
        $request['photo'] = $image_name;
        //$request['region_id'] = NULL;
        //$request['territory_id'] = NULL;
        $request['level'] = 200;
        $request['tso_id'] = Auth::id();
        $request['active'] = 1;
        $request['status'] = 1;

        $data = User::create($request->all());

        $data = retailer::create(['retailer_id' => $data->id, 'user_id' => Auth::id(), 'name' => $request['firstname'], 'officeid' => $officeid]);

        //dd($data->id);

        return redirect()->back()->with('success', 'Data has been inserted successfully');

        //========================================================================================



    }

    public function RetailerUpdate(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $user = User::find($id);

        //===========
        $active = $user->active;
        if ($active == true) {
            return redirect()->back()->withErrors('This user can not be deleted due to user status');
        }
        //===========


        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                'firstname' => 'required|min:2|max:50',
                //'lastname' => 'required|min:1|max:50',
                //'officeid' => 'required|unique:users',
                'contact' => 'required|numeric|min:1',
            ]);


            $image = $request->file('image');
            $division_id = $request->get('division_id');
            $district_id = $request->get('district_id');
            $upazila_id = $request->get('upazila_id');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $user->firstname = $request->get('firstname');
                //$user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->photo = $image_name;

                if ($division_id) {
                    $user->division_id = $division_id;
                } elseif ($district_id) {
                    $user->district_id = $district_id;
                } elseif ($upazila_id) {
                    $user->upazila_id = $upazila_id;
                }

                $user->save();

                //=================================================================

            } else {
                //$image_name = NULL;

                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                //$user->photo = $image_name;
                if ($division_id) {
                    $user->division_id = $division_id;
                } elseif ($district_id) {
                    $user->district_id = $district_id;
                } elseif ($upazila_id) {
                    $user->upazila_id = $upazila_id;
                }
                $user->save();

                //=================================================================

            }

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }



    public function RetailerDestroy($id)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $user = User::find($id);

        //===========
        $active = $user->active;
        if ($active == true) {
            return redirect()->back()->withErrors('This user can not be deleted due to user status');
        }
        //===========


        $smsdetailCount = Smsdetail::where(['user_id' => $id])->count();
        $retailerCount = Retailer::where(['user_id' => $id])->count();
        //$srCount = Sr::where(['user_id'=>$id])->count();

        $middistrictCount = Middistrict::where(['user_id' => $id])->count();


        $retailerCount2 = Retailer::where(['retailer_id' => $id])->count();
        //$srCount2 = Sr::where(['sr_id'=>$id])->count();






        if ($smsdetailCount > 0 || $retailerCount > 0 ||  $retailerCount2 > 0 || $middistrictCount > 0) {
            return redirect()->back()->withErrors('This user can not be deleted due to related to other data');
        }

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {

            $photo = $user->photo;
            $active = $user->active;

            if ($active == true) {
                return redirect()->back()->withErrors('This user can not be deleted due to user status');
            }

            if (!is_null($photo)) {
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================
            }
            //====================================

            $retailerCount1 = Retailer::where(['retailer_id' => $id])->count();
            if ($retailerCount1 > 0) {
                DB::table('retailers')->where('retailer_id', $id)->delete();
            }
            //====================================


            $user->delete();
            return redirect()->back()->with('success', 'Data has been deleted successfully');
        }
    }

    // Retailer =======================================

    //================DistributorImeiStockReportView=======================

    public function DistributorImeiStockReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $query = DB::table('purchases as t1')
            ->select(
                't1.sno as sno',
                't1.imei as imei',
                't2.firstname as distributor',
                't2.officeid as officeid',
                't4.name as brand',
                't3.name as product',
                't3.model as model'
            )
            ->join('users as t2', 't1.user_id', '=', 't2.id')
            ->join('brands as t4', 't1.brand_id', '=', 't4.id')
            ->join('products as t3', 't1.product_id', '=', 't3.id')
            ->whereNotIn('t1.sno', function ($query) {
                $query->select('sno')->from('sales');
            })
            ->where('user_id', Auth::id())
            ->orderBy('t1.id', 'desc')
            //->take(10)
            ->get();
        //->paginate(5)
        $queryresults = json_decode(json_encode($query), True);

        //dd($queryresults);


        return view('distributor.distributorImeiStock', ['queryresults' => $queryresults]);
    }

    //================DistributorImeiStockReportView======================


    //================RetailerImeiStockReportView=======================

    public function RetailerImeiStockReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $query = DB::table('sales as t1')
            ->select(
                't1.sno as sno',
                't1.imei as imei',
                't2.firstname as retailer',
                't2.officeid as retofficeid',
                't4.firstname as distributor',
                't4.officeid as distofficeid',
                't5.name as brand',
                't3.name as product',
                't3.model as model'
            )
            ->join('users as t2', 't1.ruser_id', '=', 't2.id')
            ->join('brands as t5', 't1.brand_id', '=', 't5.id')
            ->join('products as t3', 't1.product_id', '=', 't3.id')
            ->join('users as t4', 't1.user_id', '=', 't4.id')
            ->whereNotIn('t1.sno', function ($query) {
                $query->select('sno')->from('smsdetails');
            })
            ->where('user_id', Auth::id())
            ->orderBy('t1.id', 'desc')
            //->take(10)
            ->get();
        //->paginate(5)
        $queryresults = json_decode(json_encode($query), True);

        //dd($queryresults);


        return view('distributor.retailerImeiStock', ['queryresults' => $queryresults]);
    }


    public function RetailerdwnldView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $retailerReport = [];
        $distributor_id = Auth::id();
        // dd($distributor_id);
        $retailerInfo = Retailer::where('user_id', $distributor_id)->get();
        // dd($retailerInfo);
        $retailerIds = $retailerInfo->pluck('retailer_id');
        $userDetails = User::with('division', 'district', 'upazila')->find($retailerIds);
        // dd($userDetails);
        $divisionNames = $userDetails->pluck('division.name')->all();
        $districtNames = $userDetails->pluck('district.name')->all();
        $upazilaNames = $userDetails->pluck('upazila.name')->all();

        $retailerReport = [
            'retailerName' => $userDetails->pluck('firstname'),
            'contactName' => $userDetails->pluck('contact_name'),
            'email' => $userDetails->pluck('email'),
            'retailerID' => $userDetails->pluck('officeid'),
            'retailerType' => $userDetails->pluck('store_type'),
            'contactNo' => $userDetails->pluck('contact'),
            'marketName' => $userDetails->pluck('market_name'),
            'address' => $userDetails->pluck('address'),
            'division' => $divisionNames,
            'district' => $districtNames,
            'upazila' => $upazilaNames,
        ];
        // dd($retailerReport);


        return view('distributor.retailerdwnld', compact('retailerReport'));
    }

    //================RetailerImeiStockReportView======================
    //================DoaProduct=======================


    public function DoaProductView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        //Session::forget(['imei']);
        $ssdata = [];
        $data = [];
        $dataCount = 0;
        $imei = Session::get('imei');

        if ($imei) {
            $ssdata['imei'] = $imei;
            $dataCount = 1;

            $smsdetailCount = Sale::where(['imei' => $imei])->orWhere(['sno' => $imei])->count();


            if ($smsdetailCount > 0) {

                $query = Sale::with('product', 'service')->select(
                    'id',
                    'product_id',
                    'sno',
                    'imei',
                    DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate')
                )

                    //->where(['user_id' => Auth::id()])
                    // ->where('brand_id',2)
                    ->where(['imei' => $imei])
                    ->orWhere(['sno' => $imei])
                    //->take(1)
                    ->get();

                $data = json_decode(json_encode($query), True);

                //dd($data);

            }
        }




        //Session::forget(['user_id','fdate','todate']);

        return view('distributor.doaProduct', ['ssdata' => $ssdata, 'wcheckProducts' => $data, 'dataCount' => $dataCount]);
    }

    public function DoaProductService(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'cn' => 'required',
            //'imei' => 'required',
            'mobile' => 'required',
            'service_type' => 'required',
            //'imei' => 'required',
            'problem' => 'required',
        ]);


        $id = $request->get('id');
        $smsdetail = Sale::find($id);

        if ($smsdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }

        $request1['sale_id'] = $request->id;
        $request1['imei'] = $smsdetail->imei;
        $request1['sno'] = $smsdetail->sno;
        $request1['brand_id'] = $smsdetail->brand_id;
        $request1['product_id'] = $smsdetail->product_id;
        $request1['contact_name'] = $request->cn;
        $request1['contact_no'] = $request->mobile;
        $request1['service_type'] = $request->service_type;
        $request1['problem'] = $request->problem;




        Service::create($request1);



        //-------------------
        // $smsdetail->imei = $request->get('imei');
        // $smsdetail->sno = $request->get('sno');
        // $smsdetail->iswar = 0;

        //  $smsdetail->save();

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }


    public function DoaProductViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        Session::forget(['imei']);

        $this->validate($request, [
            'imei' => 'required'
        ]);


        //dd($request->all());

        $imei = $request->get('imei');

        Session::put(['imei' => $imei]);

        return redirect(route('distributor.doaProduct'));
    }

    public function Upload1View()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }



        return view('distributor.upload1');
    }

    public function Upload1ViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $type = $request->type;
        //==================================
        $image = $request->file('csv_file');

        if (!is_null($image)) {

            $this->validate($request, [
                'csv_file' => 'required|mimes:csv,txt|max:200000',
            ]);
        }




        if ($type == 12) {
            // For product specification-------------------
            $path = $request->file('csv_file')->getRealPath();
            $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
            $data = array_map('str_getcsv', file($path));
            $csv_data = array_slice($data, 1, count($row_index));

            // Initialize memo number generation variables
            $currentRetailer = null;
            $memoIncrement = 0;
            $maxId = DB::table('sales')->max('id'); // Get max ID once

            //===============================================
            foreach ($csv_data as $key => $value) {
                $retailer = $value[0];
                $sno = $value[1];

                // Check conditions in the database
                $count1 = DB::table('stocks')->select('id')->where(['sno' => $sno])->count();
                $count2 = DB::table('sales')->select('id')->where(['sno' => $sno])->count();
                $count3 = DB::table('users')->select('id')->where(['officeid' => $retailer, 'level' => 200])->count();
                $count4 = DB::table('purchases')->select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->count();

                if ($count1 > 0 && $count2 < 1 && $count3 > 0 && $count4 > 0) {
                    // Generate memo number for the current retailer
                    if ($currentRetailer !== $retailer) {
                        // If it's a new retailer, generate a new memo number with increment
                        $memoNumber = $maxId . $memoIncrement;  // Concatenate max(id) with the increment value
                        $currentRetailer = $retailer; // Update the current retailer being processed
                        $memoIncrement++; // Increment for next new retailer
                    }

                    // Retrieve user and retailer data
                    $query = User::where(['officeid' => $retailer, 'level' => 200])->first();
                    $users = $query->toArray();
                    $user_id = $users['id'];

                    $mapping = Retailer::where([
                        'retailer_id' => $user_id,
                        'user_id' => Auth::id()
                    ])->first();

                    if (!$mapping) {
                        continue; // Skip if no mapping found
                    }

                    $count5 = DB::table('retailers')->select('id')->where(['retailer_id' => $user_id])->count();

                    if ($count5 > 0) {
                        $query = Purchase::where(['sno' => $sno])->first();
                        $purchases = $query->toArray();
                        $distributor_id = $purchases['user_id'];

                        $query = Retailer::where(['retailer_id' => $user_id])->first();
                        $retailers = $query->toArray();
                        $retailer_id = $retailers['id'];
                        $user_id = $retailers['retailer_id'];

                        // Retrieve stock data
                        $stockresult = DB::table('stocks')->select('id', 'product_id', 'imei', 'sno', 'brand_id')->where(['sno' => $sno])->take(1)->first();
                        $stockdata = json_decode(json_encode($stockresult), True);

                        // Prepare data for insertion
                        $data3 = [
                            'user_id' => $distributor_id,
                            'ruser_id' => $user_id,
                            'retailer_id' => $retailer_id,
                            'memo' => $memoNumber,
                            'stock_id' => $stockdata['id'],
                            'product_id' => $stockdata['product_id'],
                            'brand_id' => $stockdata['brand_id'],
                            'imei' => $stockdata['imei'],
                            'sno' => $stockdata['sno']
                        ];

                        // Insert into Sales table
                        Sale::create($data3);
                    }
                }
            }


            //===============================================
            //===============================================


            //====================================================
            $userCount = 0;
            $snoCount = 0;
            $snodata = [];
            $userdata = [];

            foreach ($csv_data as $key => $value) {



                $retailer = $value[0];
                $sno = $value[1];

                //---------------------------------------
                $count1 = DB::table('stocks')->select('id')->where(['sno' => $sno])->count();
                $count2 = DB::table('sales')->select('id')->where(['sno' => $sno])->count();
                $count3 = DB::table('users')->select('id')->where(['officeid' => $retailer, 'level' => 200])->count();

                $count4 = DB::table('purchases')->select('id')->where(['sno' => $sno, 'user_id' => Auth::id()])->count();

                if ($count1 < 1 || $count4 < 1) {
                    //return redirect()->back()->withErrors("Duplicate serial no :  $sno can not be taken")->withInput();

                    $snodata[] = $sno;
                    $snoCount += 1;
                }

                $count = DB::table('users')->select('id')->where(['officeid' => $retailer, 'level' => 200])->count();

                if ($count < 1) {
                    //return redirect()->back()->withErrors("There is no retailer as ID $retailer")->withInput();

                    $userdata[] = $retailer;
                    $userCount += 1;
                }



                //---------------------------------------

            }
            // for product specification-------------------

            //----------------

            if ($snoCount > 0 || $userCount > 0) {
                $user_implode = implode(", ", $userdata);
                $sno_implode = implode(", ", $snodata);

                return redirect()->back()->withErrors("UID : $user_implode </br> SNO : $sno_implode
      </br>
      can not be uploaded, please correction csv file first then upload")->withInput();
            }
            //----------------



            //===============================================


        }

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }

    //================DoaProduct=======================

    //================DailyReplaceReport=======================


    public function DailyReplaceReportViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyReplaceReports = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
        }




        $pdf = PDF::loadView('distributor.dailyReplaceReports_print', ['ssdata' => $ssdata, 'dailyReplaceReports' => $dailyReplaceReports, 'totalamount' => $totalamount]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userdailyReplaceReports.pdf');
    }



    public function DailyReplaceReportView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);


        $fdate = Session::get('fdate');
        $todate = Session::get('todate');


        $dataCount = 0;
        $ssdata = [];
        $dailyReplaceReports = [];

        if ($fdate && $todate) {
            $dataCount = 1;

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;


            $dailyReplaceReports = Replace::with('smsdetail', 'user')
                ->select('id', 'user_id', 'smsdetail_id', 'contact_name', 'contact_no', 'service_type', 'problem', 'service_status', 'memo', 'received', 'replace_imei2', 'delivery_date', 'remarks', 'void', 'imei', 'sno', DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as rplsdate'))
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->where('user_id', Auth::user()->id)
                ->get();


            // dd($dailyReplaceReports);

            // exit();
        }



        return view('distributor.dailyReplaceReport', ['ssdata' => $ssdata, 'dailyReplaceReports' => $dailyReplaceReports, 'dataCount' => $dataCount]);
    }


    public function DailyReplaceReportViewStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        //dd($request->all());


        Session::forget(['fdate', 'todate']);

        $this->validate($request, [
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('distributor.dailyReplaceReport'));
    }

    //================DailyReplaceReport======================
    public function DashView()
    {
        if (Auth::user()->level != 10) {
            return redirect()->route('logout');
        }
        $_SESSION['favicon'] = self::$favicon;
        $_SESSION['logo'] = self::$logo;
        $oraderList = Order::where('user_id', Auth::id())->get();
        $productList = Product::select('id', 'model', 'name', 'dp', 'color')->get();
        $upazilaResult = Tsoupazila::select('id', 'name', 'bn_name', 'upazila_id')
            ->where(['user_id' => Auth::id()])
            ->orderBy('id', 'desc')
            ->get();
        $upazilas = $upazilaResult->toArray();
        return view('tso.orader.list', ['upazilas' => $upazilas, 'productList' => $productList, 'oraderList' => $oraderList]);
    }

    //================RetailerImeiStockReportView======================
    public function TerViewExcel()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        // Get a list of retailer IDs associated with the current user
        $retailerIds = Retailer::where('user_id', Auth::user()->id)
            ->pluck('retailer_id')
            ->toArray();

        $stocks = [];

        foreach ($retailerIds as $retailerId) {
            // Your existing code here
            $query = DB::table('smsdetails as t1')
                ->select(
                    't8.firstname AS retailer_name',
                    't8.officeid AS retailer_id',
                    't3.name AS product',
                    't3.model AS model',
                    't4.name AS brand',
                    't3.color AS color',
                    't1.sno AS sno',
                    't1.imei AS imei',
                    't1.mobile AS Mobile',
                    't1.wperiod AS warranty_period(days)',
                    // DB::raw("DATE_FORMAT(DATE_ADD(t1.created_at, INTERVAL t1.wperiod DAY),"%m/%d/%Y") as edate',
                    DB::raw("DATE_FORMAT(t1.created_at, '%Y-%m-%d') as sale_date"),
                    DB::raw("DATE_FORMAT(DATE_ADD(t1.created_at, INTERVAL t1.wperiod DAY),'%Y-%m-%d') as warranty_end_date")


                )
                ->join('brands as t2', 't1.brand_id', '=', 't2.id')
                ->join('products as t3', 't1.product_id', '=', 't3.id')
                ->join('users as t8', 't1.user_id', '=', 't8.id')
                ->join('brands as t4', 't1.brand_id', '=', 't4.id')
                ->where('t1.user_id', $retailerId)
                ->orderBy('t1.id', 'desc')
                ->take(10000000)
                ->get();

            $retailerStocks = json_decode(json_encode($query), true);

            // Append retailer-specific data to the $stocks array
            $stocks = array_merge($stocks, $retailerStocks);
        }

        return (new FastExcel($stocks))->download('tertiary.xlsx');
    }

    public function purchaseCurrentMonthExcel()
    {
        $user_id = Auth::id();

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $purchases = Purchase::where('user_id', $user_id)
            ->where('status', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->take(200000)
            ->get();

        $purchaseData = $purchases->map(function ($purchase) {
            return [
                'Name' => $purchase->user->firstname,
                'Product Name' => $purchase->product->name,
                'Product Model' => $purchase->product->model,
                'IMEI 1' => $purchase->sno,
                'IMEI 2' => $purchase->imei,
                'Purchase Date' => $purchase->created_at->format('Y-m-d'),
            ];
        });

        return (new FastExcel($purchaseData))->download('purchases.xlsx');
    }


    public function purchaseSixMonthExcel()
    {
        $user_id = Auth::id();

        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        $purchases = Purchase::where('user_id', $user_id)
            ->where('status', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->take(200000)
            ->get();

        $purchaseData = $purchases->map(function ($purchase) {
            return [
                'Name' => $purchase->user->firstname,
                'Product Name' => $purchase->product->name,
                'Product Model' => $purchase->product->model,
                'IMEI 1' => $purchase->sno,
                'IMEI 2' => $purchase->imei,
                'Purchase Date' => $purchase->created_at->format('Y-m-d'),
            ];
        });

        return (new FastExcel($purchaseData))->download('purchases.xlsx');
    }


    public function saleCurrentMonthExcel()
    {
        $user_id = Auth::id();

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $sales = Sale::where('user_id', $user_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->take(200000)
            ->get();

        $saleData = $sales->map(function ($sale) {
            return [
                'SR' => '-',
                'Retailer' => $sale->retailer->name,
                'Product Name' => $sale->product->name,
                'Product Model' => $sale->product->model,
                'IMEI 1' => $sale->sno,
                'IMEI 2' => $sale->imei,
                'Sale Date' => $sale->created_at->format('Y-m-d'),
            ];
        });

        return (new FastExcel($saleData))->download('sales.xlsx');
    }


    public function saleSixMonthExcel()
    {
        $user_id = Auth::id();

        $startDate = now()->subMonths(5)->startOfMonth();
        $endDate = now()->endOfMonth();

        $sales = Sale::where('user_id', $user_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->take(200000)
            ->get();

        $saleData = $sales->map(function ($sale) {
            return [
                'SR' => '-',
                'Retailer' => $sale->retailer->name,
                'Product Name' => $sale->product->name,
                'Product Model' => $sale->product->model,
                'IMEI 1' => $sale->sno,
                'IMEI 2' => $sale->imei,
                'Sale Date' => $sale->created_at->format('Y-m-d'),
            ];
        });

        return (new FastExcel($saleData))->download('sales.xlsx');
    }


    public function SRView()
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }
        $distributor_id = Auth::id();
        // dd($distributor_id);

        $userResult = SR::where('user_id', $distributor_id)->paginate(300);

        return view('distributor.sr', ['salesrepresentatives' => $userResult]);
    }

    public function SRStore(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $distributor_id = Auth::id();


        $this->validate($request, [
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            'firstname' => 'required|min:2|max:50',
            //'lastname' => 'required|min:1|max:50',
            'email' => 'required|email|unique:users',
            'officeid' => 'required|unique:users',
            'password' => 'required|min:3|max:20',
            'confirm_password' => 'required|min:3|max:20|same:password',
            'contact' => 'required|numeric|min:1',
            //'level' => 'required'
        ]);

        $image = $request->file('image');

        if (!is_null($image)) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]);


            $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
            Storage::put($image_name, file_get_contents($image));
        } else {
            $image_name = NULL;
        }

        $data['firstname'] = $request['firstname'];
        $data['email'] = $request['email'];
        $data['officeid'] = $request['officeid'];
        $data['contact'] = $request['contact'];
        $data['remember_token'] = $request['_token'];
        $data['password'] = bcrypt($request['password']);
        $data['photo'] = $image_name;
        //$data['region_id'] = NULL;
        //$data['territory_id'] = NULL;
        $data['level'] = 50;

        $user = User::create($data);

        $data1['sr_id'] = $user->id;
        $data1['user_id'] = $distributor_id;
        $data1['name'] = $request['firstname'];
        $data1['email'] = $request['email'];
        $data1['officeid'] = $request['officeid'];
        $data1['levelname'] = "SR";

        SR::create($data1);


        return redirect()->back()->with('success', 'SR has been created successfully');
    }

    public function SRUpdate(Request $request)
    {
        if (Auth::user()->level != 100) {
            return redirect()->route('logout');
        }

        $id = $request->get('id');
        $srInfo = SR::find($id);

        $sr_id = $srInfo->sr_id;
        // dd($sr_id);

        $user = User::find($sr_id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            // $this->validate($request, [
            //  //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            //  'firstname' => 'required|min:2|max:50',
            //  'contact_name' => 'required|min:2|max:50',
            //  'market_name' => 'required|min:2|max:50',
            //  'store_type' => 'required|min:2|max:50',
            //  //'lastname' => 'required|min:1|max:50',
            //  //'officeid' => 'required|unique:users',
            //  'contact' => 'required|numeric|min:1',
            //  'address' => 'required|min:2|max:99',
            // ]);


            $image = $request->file('image');
            $division_id = $request->get('division_id');
            $district_id = $request->get('district_id');
            $upazila_id = $request->get('upazila_id');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->contact_name = $request->get('contact_name');
                $user->market_name = $request->get('market_name');
                $user->store_type = $request->get('store_type');
                //$user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->address = $request->get('address');
                $user->photo = $image_name;

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;

                $user->save();

                //=================================================================

            } else {
                //$image_name = NULL;

                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                $user->contact_name = $request->get('contact_name');
                $user->market_name = $request->get('market_name');
                $user->store_type = $request->get('store_type');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->address = $request->get('address');
                //$user->photo = $image_name;

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;



                $user->save();



                //=================================================================

            }

            return redirect()->back()->with('success', 'SR Information has been updated successfully');
        }
    }

    public function SRDestroy($id)
    {
        // dd($id);
        $srInfo = SR::find($id);

        $sr_id = $srInfo->sr_id;
        // dd($sr_id);

        $user = User::find($sr_id)->delete();
        $srInfo->delete();

        return redirect()->back()->with('success', 'SR has been deleted successfully');
    }

    public function retailerEdit()
    {
        $user_id = Auth::id();
        $retailers = Retailer::where('user_id', $user_id)->get();


        $retailerList = [];



        foreach ($retailers as $retailer) {
            $user = User::find($retailer->retailer_id);

            if ($user) {
                $retailerList[] = [
                    'retailerName' => $user->firstname,
                    'retailerCode' => $user->officeid,
                    'contactName' => $user->contact_name,
                    'contact' => $user->contact,
                    'marketName' => $user->market_name,
                    'address' => $user->address,
                    'id' => $user->id,
                ];
            }
        }

        // dd($retailerList);
        return view('distributor.retailerUpdate', compact('retailerList'));
    }

    public function retailerEditUpdate(Request $request)
    {
        $retailer_id = $request->user_id;
        $retailerName = $request->retailerName;
        $contactName = $request->contactName;
        $contact = $request->contact;
        $marketName = $request->marketName;
        $address = $request->address;

        User::find($retailer_id)->update(['firstname' => $retailerName, 'contact_name' => $contactName, 'contact' => $contact, 'market_name' => $marketName, 'address' => $address]);
        Retailer::where('retailer_id', $retailer_id)->update(['name' => $retailerName]);

        return redirect()->back()->with('success', 'Retail Information has been updated successfully');
    }
}
