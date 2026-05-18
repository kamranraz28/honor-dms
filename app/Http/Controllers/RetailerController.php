<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use App\Http\Requests\ClassName;
//use App\Http\Controllers\AuthController;

use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;

use Redirect;
use Validator;
use Input;
use Session;
use Auth;
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


use App\Division;
use App\District;
use App\Upazila;

use App\Purchase;
use App\Sale;
use App\Preturn;
use App\Retailer;
use App\Dwdetail;
use App\Service;



class RetailerController extends Controller
{
  
  public static $code;
  public static $currency;
  public static $timezone;
  public static $contact;
  public static $vat;
  public static $semail;
  public static $favicon;
  public static $logo;


  public function __construct(){
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

  private function security(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
  }

  public function DashboardView(){
     if (Auth::user()->level != 200) { return redirect()->route('logout');}

     //dd(Auth::id());


    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    $data['totalSale'] = Smsdetail::select('id')->where('user_id',Auth::id())->count();
    $data['monthlySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-m") )->count();
    $data['todaySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date("Y-m-d") )->count();
     
    return view('retailer.dashboard',['data' => $data]);
    
  }

  public function Test(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    return "this is retailer testing page";
  }




//================DailySalesReport=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    //-------------------------
  if (!$fdate || !$todate || !$user_id) {
    return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
  }else{
    Session::put(['user_id'=> $user_id,'fdate'=> $fdate,'todate'=>$todate]);
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




    $pdf = PDF::loadView('retailer.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    $userResult = User::select('id','firstname','officeid')->where('level',200)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();



    $brand_id = Session::get('brand_id');
    $user_id = Auth::id();
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');



    $ssdata = [];
    $totalamount = [];
    $dailySalesReports = [];


    if ($brand_id == 'all' && $fdate && $todate && !$user_id && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;

        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id()])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && !$user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id()])
          ->where(['brand_id'=>$brand_id])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && $user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id()])
          //->where(['user_id'=>$user_id])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && !$user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id()])
          ->where(['sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && $user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id(),'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && $user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['brand_id'=>$brand_id,'user_id'=>Auth::id()])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && !$user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['user_id'=>Auth::id()])
          ->where(['brand_id'=>$brand_id,'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && $user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailySalesReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          //->where(['status'=>0])
          ->where(['brand_id'=>$brand_id,'user_id'=>Auth::id(),'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }else{
      $dailySalesReports = [];
    }


//dd($dailySalesReports);

//Session::forget(['brand_id','user_id','sno','fdate','todate']);

    return view('retailer.dailySalesReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);

  }


  public function DailySalesReportViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['brand_id','sno','fdate','todate']);

    $this->validate($request, [
      'brand_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $brand_id = $request->get('brand_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['brand_id'=>$brand_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('retailer.dailySalesReport'));


  }

//================DailySalesReport======================

//================DailyCampaignReport=======================

  
  public function DailyCampaignReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    //-------------------------
  if (!$fdate || !$todate || !$user_id) {
    return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
  }else{
    Session::put(['user_id'=> $user_id,'fdate'=> $fdate,'todate'=>$todate]);
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




    $pdf = PDF::loadView('retailer.dailyCampaignReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailyCampaignReportView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    $userResult = User::select('id','firstname','officeid')->where('id',Auth::id())->where('level',200)->orderBy('id','desc')->get();
    $users = $userResult->toArray();


    $brand_id = Session::get('brand_id');
    $user_id = Auth::id();
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');



    $ssdata = [];
    $totalamount = [];
    $dailyCampaignReports = [];

    if ($brand_id == 'all' && $fdate && $todate && !$user_id && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;

        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['user_id'=>Auth::id()])
          ->where(['status'=>1])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && !$user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['user_id'=>Auth::id()])
          ->where(['status'=>1])
          ->where(['brand_id'=>$brand_id])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && $user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['status'=>1])
          ->where(['user_id'=>$user_id])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && !$user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['user_id'=>Auth::id()])
          ->where(['status'=>1])
          ->where(['sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id == 'all' && $fdate && $todate && $user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['status'=>1])
          ->where(['user_id'=>$user_id,'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && $user_id && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['status'=>1])
          ->where(['brand_id'=>$brand_id,'user_id'=>$user_id])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && !$user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['user_id'=>Auth::id()])
          ->where(['status'=>1])
          ->where(['brand_id'=>$brand_id,'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }elseif($brand_id != 'all' && $fdate && $todate && $user_id && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
          ->where(['status'=>1])
          ->where(['brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }else{
      $dailyCampaignReports = [];
    }

//dd($dailyCampaignReports);

//Session::forget(['brand_id','user_id','sno','fdate','todate']);

    return view('retailer.dailyCampaignReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailyCampaignReports'=>$dailyCampaignReports]);

  }


  public function DailyCampaignReportViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['brand_id','user_id','sno','fdate','todate']);

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
    
    Session::put(['brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('retailer.dailyCampaignReport'));


  }

//================DailySalesReport======================





//================WcheckProduct=======================

  
  public function WcheckProductViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    //-------------------------
  if (!$fdate || !$todate || !$user_id) {
    return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
  }else{
    Session::put(['user_id'=> $user_id,'fdate'=> $fdate,'todate'=>$todate]);
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




    $pdf = PDF::loadView('retailer.wcheckProducts_print',['ssdata'=>$ssdata,'wcheckProducts'=>$wcheckProducts,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userwcheckProducts.pdf');

  }


  
  public function WcheckProductView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}

    
//Session::forget(['imei']);
    $ssdata = [];
    $data = [];
    $dataCount = 0;
    $imei = Session::get('imei');

    if($imei){
        $ssdata['imei'] = $imei;
        $dataCount = 1;

        $smsdetailCount = Smsdetail::where(['imei' => $imei])->orWhere(['sno'=>$imei])->count();
if ($smsdetailCount > 0) {
        
        $query = Smsdetail::with('product','replace', 'service')->select('id','product_id','promo_id','promodetail_id','sno','imei','wperiod','iswar',
          DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))

                  ->where(['user_id' => Auth::id()])
                  ->where(['imei' => $imei])
                  ->orWhere(['sno'=>$imei])
                  //->take(1)
                  ->get();

        $data = json_decode(json_encode($query), True);       

        //dd($data);

}





    }




//Session::forget(['user_id','fdate','todate']);

    return view('retailer.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }

public function WcheckProductService(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}


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





  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('retailer.wcheckProduct'));


  }

//================WcheckProduct=======================





// Retailer =======================================
  
  public function RetailerView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    //$userCount = User::count();
    
    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where('id',Auth::id())->where('level',200)->orderBy('id','desc')->paginate(300);
    //$users = $userResult->toArray();

//dd($users);
    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('retailer.retailer',['users'=>$userResult,
      'divisions'=>$divisions, 'districts'=>$districts,'upazilas'=>$upazilas]);

  }


  public function RetailerUpdate(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        'firstname' => 'required|min:2|max:50',
        //'lastname' => 'required|min:1|max:50',
        //'officeid' => 'required|unique:users',
        'contact' => 'required|numeric|min:1',
      ]); 


      $image = $request->file('image');
      //$attachment = $request->file('attachment');

      $division_id = $request->get('division_id');
      $district_id = $request->get('district_id');
      $upazila_id = $request->get('upazila_id');

      if (!is_null($image)) {

        $this->validate($request, [
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        ]);
// for deleting file =======================
        File::delete('storage/app/' . $user['photo']);
// for deleting file =======================

        $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
        Storage::put($image_name, file_get_contents($image));
      //=================================================================
        $user->firstname = $request->get('firstname');
        $user->lastname = $request->get('lastname');
        //$user->email = $request->get('email');
        //$user->officeid = $request->get('officeid');
        $user->contact = $request->get('contact');
        $user->photo = $image_name;

        $user->division_id = $division_id;
        $user->district_id = $district_id;
        $user->upazila_id = $upazila_id;

        $user->save();

      //=================================================================

        }else{
          //$image_name = NULL;

      //=================================================================
        $user->firstname = $request->get('firstname');
        $user->lastname = $request->get('lastname');
        //$user->email = $request->get('email');
        //$user->officeid = $request->get('officeid');
        $user->contact = $request->get('contact');
        //$user->photo = $image_name;
        $user->division_id = $division_id;
        $user->district_id = $district_id;
        $user->upazila_id = $upazila_id;

        
        $user->save();

      //=================================================================

        }

    return redirect()->back()->with('success', 'Data has been updated successfully');   
 
  }

 
  }


  public function UpdatePassword(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}    
    
    //dd($request->all());

    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        'password' => 'required|min:3|max:200',
        'confirm_password' => 'required|min:3|max:200|same:password',
      ]); 

      $user->password = bcrypt($request['password']);
      $user->save();

    }

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }




// Retailer =======================================


  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}


    $this->validate($request, [
      'id' => 'required',
      //'imei' => 'required',
      'sno' => 'required',
    ]);
    


    $id = $request->get('id');
    $smsdetail = Smsdetail::find($id);
    
    if ($smsdetail === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }

    $request1['smsdetail_id'] = $request->id;
    $request1['sno'] = $smsdetail->sno;
    $request1['imei'] = $smsdetail->imei;
    Replace::create($request1);



//-------------------
    $smsdetail->imei = $request->get('imei');
    $smsdetail->sno = $request->get('sno');
    $smsdetail->iswar = 0;
    
    return redirect()->back()->with('success', 'Data has been inserted successfully');


  }



// ReturnProduct =======================================
  
  public function ReturnProductView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    

//dd(Auth::id());

    $count = Retailer::where('retailer_id',Auth::id())->count();

    if ($count < 1) {
      return redirect()->route('retailer.dashboard')->withErrors('You are not allowed for return oprion');
    }


    $preturns = Preturn::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at','updated_at','status')->where('ruser_id',Auth::id())->paginate(200);
    //$sales = $saleResult->toArray();

    //dd(Auth::id());



    return view('retailer.returnProduct',['preturns'=>$preturns]);

  }

  public function ReturnProductViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    
    $this->validate($request,['snos'=>'required']);

    $snos =array_unique($request->snos);
    $data = [];
    
    foreach ($snos as $key => $value) {
        $sno = $value;

        $count = Sale::select('id')->where(['sno'=>$sno,'ruser_id'=>Auth::id()])->count();
        $count1 = Smsdetail::select('id')->where(['sno'=>$sno,'user_id'=>Auth::id()])->count(); 
        $count2 = Stock::select('id')->where(['sno'=>$sno])->count();

        $count3 = Preturn::select('id')->where(['sno'=>$sno])->count();

       if ($count == 0) {
        return redirect()->back()->withErrors('This s.no is not allowed for returning')->withInput();
       }else if ($count1 > 0) {
        return redirect()->back()->withErrors('This s.no has all ready been sold')->withInput();
       }else if ($count2 == 0){
        return redirect()->back()->withErrors('This s.no does not match')->withInput();
       }else if ($count3 > 0 ){
        return redirect()->back()->withErrors('This s.no has already in return process')->withInput();
       }

    }


    foreach ($snos as $key => $value) {
      $sno = $value;
    //=================================
      $saleResult = Sale::where(['sno'=>$sno,'ruser_id'=>Auth::id()])->first();
      $sales = $saleResult->toArray();

    //=================================
      Preturn::create($sales);

    }

    
    return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function ReturnProductDelete($id = null){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
 
    $Preturn = Preturn::find($id);
    
    $status =$Preturn->status;
    if ($Preturn === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      if ($status > 1) {
        return redirect()->back()->withErrors('This item can not be deleted becouse of distributor approved this item');
      }else{
        $Preturn->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }
    

 
  }



// ReturnProduct =======================================




// DontWorry =======================================
  
  public function DontWorryView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    

    $count = user::where(['id'=>Auth::id(), 'isdw'=>0])->count();

    if ($count > 0) {
      return redirect()->route('retailer.dashboard')->withErrors('You are not allowed for dont worry option');
    }

    
    $dwrecords = [];

    $query = Dwdetail::with('product','brand','user')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $dwrecords = $query->toArray();


    //dd($dwrecords);


    return view('retailer.dontWorry',['dwrecords'=>$dwrecords]);

  }

  public function DontWorryViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    
    $this->validate($request,['imei'=>'required','customer'=>'required','mobile'=>'required']);

    $imei = $request->imei;
    $mobileno = $request->mobile;
    $count = Stock::where(['sno' => $imei])->count();

    if ($count < 1) {
      return redirect()->back()->withErrors('IMEI is not valid');
    }


    //================
    $arrayData = [];

  if ($count > 0) {
    $stockResult = Stock::where(['sno' => $imei])->first();
      $stocks = $stockResult->toArray();
      $product_id = $stocks["product_id"];
      

      $count = Product::where(['id' => $product_id, 'dwstatus' => 1])->count();

      if ($count > 0) {
        $productResult = Product::where(['id' => $product_id, 'dwstatus' => 1])->first();
        $products = $productResult->toArray();

        $brand_id = $products["brand_id"];
        $product_id = $products["id"];
        $product = $products["name"];
        $dwcharge = $products["dwcharge"];
        $dwday = $products["dwday"];
        $dwduration = $products["dwduration"];

        $count = Smsdetail::where(['sno' => $imei])->count();

        if ($count > 0 ) {
            $smsdetailResult = Smsdetail::where(['sno' => $imei])->first();
            $smsdetails = $smsdetailResult->toArray();
            $created_at = $smsdetails["created_at"];

            $date1 = strtotime(date_format(date_create($created_at),"Y-m-d"));
            $date2 = strtotime(date_format(date_create(date("Y-m-d h:i:s")),"Y-m-d"));
            $wperiod = ($date2 - $date1)/86400;

          if ($dwduration < $wperiod ) {
            return redirect()->back()->withErrors("Not valid customer. Don't Worry Offer is Valid before ". ($wperiod - $dwduration) ." days of Purchase");
          }

          $arrayData = array('dwstatus' =>1,'status' =>1,'dwduration' =>$dwduration,'product'=>$product,'dwcharge'=>$dwcharge,'dwday' =>$dwday,'brand_id' =>$brand_id,'product_id' =>$product_id);
        }else{
          $arrayData = array('dwstatus' =>1,'status' =>0,'dwduration' =>$dwduration,'product'=>$product,'dwcharge'=>$dwcharge,'dwday' =>$dwday,'brand_id' =>$brand_id,'product_id' =>$product_id);
        }
      }else{
        return redirect()->back()->withErrors('Don\'t worry offer is not activated for this product');
      }
  }else{
    return redirect()->back()->withErrors('IMEI is not valid');
  }

    //================
    $arrayData["user_id"] = Auth::id();
    $arrayData["sno"] = $request->get("imei");
    $arrayData["customer"] = $request->get("customer");
    $arrayData["mobile"] = $request->get("mobile");

    Dwdetail::create($arrayData); 
    //dd($arrayData);

    if ($arrayData["dwstatus"] == 1 && $arrayData["status"] == 1) {
      //Update sms details==========
        $smsdetailResult = Smsdetail::where(['sno' => $imei])->first();
        $smsdetails = $smsdetailResult->toArray();
        $twperiod = $smsdetails["wperiod"] + $arrayData["dwday"];
        DB::table('smsdetails')->where('sno', $imei)->update(['isdw' => 1,'dwday' => $arrayData["dwday"], 'twperiod' => $twperiod ]);
      //Update sms details==========
        


      $msg = "Congratulations your worranty have extended for " . $arrayData["dwday"] . " days";
      //self::send_msg($mobileno,$msg);

      self::jhorotek_sms_service($mobileno,$msg,$user='nonmask@synergyinterface.com',$pass='info@Pass',$masking='NoMask');

    }elseif($arrayData["dwstatus"] == 1 && $arrayData["status"] == 0){
      $msg = "Thank you, your don't worry offer is pending for approvale within 72 hours you will be notifed by sms ";
     //self::send_msg($mobileno,$msg);
       self::jhorotek_sms_service($mobileno,$msg,$user='nonmask@synergyinterface.com',$pass='info@Pass',$masking='NoMask');
    }


    return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }


  private static function send_msg($mobileno,$msg){
    $phoneno = str_replace("+", "", $mobileno); 
    $getdata = http_build_query(
      array(
          'masking' => 'SMART TECH',
          'userName' => 'SmartTech_Sofel',
          'password'=>'46fb610d839ea46f08f7ab8810686e19',
          'MsgType'=>'TEXT',
          'receiver'=>$phoneno,
          'message'=>$msg,
        )
    );

    $opts = array('http' =>
      array(
        'method'  => 'GET',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $getdata
      )
    );

    $context  = stream_context_create($opts);
     
   return file_get_contents ('http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?'.$getdata, false, $context);

  }


  private static function jhorotek_sms_service($mobile,$smsBody,$user='nonmask@synergyinterface.com',$pass='info@Pass ',$masking='NoMask'){

      $sms_array = array(); 

      //create a json array of your sms 
      $row_array['trxID'] =     self::udate('YmdHisu');
      $row_array['trxTime'] = date('Y-m-d H:i:s'); 

      $mySMSArray [0]['smsID'] = self::udate('YmdHisu'); 
      $mySMSArray [0]['smsSendTime'] = date('Y-m-d H:i:s'); 
      $mySMSArray [0]['mask'] = $masking;
      //$mySMSArray [0]['mobileNo'] = '8801777001014'; 
      //$mySMSArray [0]['smsBody'] = 'Testing from infobuzzer to Ringku'; 

      $mySMSArray [0]['mobileNo'] = $mobile; 
      $mySMSArray [0]['smsBody'] = $smsBody;


      $row_array['smsDatumArray'] = $mySMSArray; 

      $myJSonDatum = json_encode($row_array); 

      //specifi the url 
      //$url="http://api.infobuzzer.net/v3.1/SendSMS/sendSmsInfoStore"; 
      $url="http://api.infobuzzer.net/v3.1/index.php/SendSMS/sendSmsInfoStore"; 

      if($ch = curl_init($url)) 
      { 
          //Your valid username & Password ----------Please update those field 
          //$username = 'info@synergyinterface1.com'; 
          //$password = 'info@Pass'; 


          $username = $user; 
          $password = $pass; 

          curl_setopt( $ch , CURLOPT_HTTPAUTH , CURLAUTH_BASIC ) ; 
          curl_setopt( $ch, CURLOPT_USERPWD , $username . ':' . $password ) ; 
          curl_setopt( $ch , CURLOPT_CUSTOMREQUEST , 'POST' ) ; 

          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 
              'Content-Length: ' . strlen($myJSonDatum))); 

          curl_setopt($ch, CURLOPT_POST, true); 
          curl_setopt($ch, CURLOPT_POSTFIELDS,$myJSonDatum); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

          curl_setopt( $ch, CURLOPT_TIMEOUT , 300 ) ; 
          $response=curl_exec($ch); 
          curl_close($ch); 
          //return $json = json_decode($response, true);
          //echo('Response is: '.$response);
          
          //$json['status'] ." ".$json['success']." ".$json['reason'];
          //return  "Response is" . $json;
      } 
      else 
      { 
        return "Sorry,the connection cannot be established"; 
      } 


    }


    private static function udate($format, $utimestamp = null) 
    { 
      $m = explode(' ',microtime()); 
      list($totalSeconds, $extraMilliseconds) = array($m[1], (int)round($m[0]*1000,3));
      return date("YmdHis", $totalSeconds) . sprintf('%03d',$extraMilliseconds) ; 
    } 

// DontWorry =======================================




  public function varifyserialnoOne($no){
    //if (Auth::user()->level != 100) { return redirect()->route('logout');}
     
    $countt = Preturn::select('id')->where(['sno'=>$no,'ruser_id'=>Auth::id()])->count(); 
    $count = Sale::select('id')->where(['sno'=>$no,'ruser_id'=>Auth::id()])->count();
    $count1 = Smsdetail::select('id')->where(['sno'=>$no,'user_id'=>Auth::id()])->count(); 
    $count2 = Stock::select('id')->where(['sno'=>$no])->count();



     if ($count == 0 || $countt > 0) {
      return 0;
     }else if ($count1 > 0) {
      return 1;
     }else if ($count2 == 0){
      return 2;
     }else{
      return 3;
     }

  }


  public function ActivewarrantyView(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    return view('retailer.activewarranty');
  }

  public function ActivewarrantyViewStore(Request $request){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    //dd($request->all());
    $this->validate($request,[
      //'user_id'=>'required',
      'sno'=>'required',
      'mobile'=>'required',
      //'fdate'=>'required',
    ]);

    $user_id = Auth::id();
    $sno = $request->sno;
    $mobile = $request->mobile;
    //$fdate = $request->fdate;




    $stockcount = Stock::where(['sno'=>$sno])->count(); 
    if ($stockcount < 1) {
      return redirect()->back()->withErrors("IMEI or SNO is not valid ")->withInput();
    }

    $smscount = Smsdetail::where(['sno'=>$sno])->count(); 
    if ($smscount > 0) {
      return redirect()->back()->withErrors("This IME/SNO is already sold!! ")->withInput();
    }

    $smscount = Smsdetail::where(['sno'=>$sno])->count(); 

if ($smscount < 1) {
  //$data1['created_at'] = date_format(date_create($fdate),"Y-m-d H:i:s");
//---------------------------------------
    $productResult = DB::table('stocks')->select('id','product_id','brand_id',
      'wperiod','imei')->where(['sno'=>$sno])->first();
    $productdata = json_decode(json_encode($productResult), True);
    
    $data1['product_id'] = $productdata['product_id'];
    $data1['brand_id'] = $productdata['brand_id'];
    $data1['wperiod'] = $productdata['wperiod'];
    $data1['imei'] = $productdata['imei'];
    $data1['user_id'] = $user_id;
    $data1['sno'] = $sno;
//---------------------------------------
    


      $data1['mobile'] = $mobile;

      $data1['promo_id'] = 0;
      $data1['promodetail_id'] = 0;

      $data1['status'] = 0;


//----------------------------------------
    $dwcount = Dwdetail::where(['sno'=>$sno, 'status' => 0])->count();  

    if ($dwcount > 0) {
      DB::table('dwdetails')->where('sno', $sno)->update(['status' => 1]);
      
      $dwdetail = Dwdetail::where(['sno'=>$sno])->first();    
      
      //$sno = $dwdetail->sno;
      $dwday = $dwdetail->dwday;
      $dwcharge = $dwdetail->dwcharge;
      
      $mobileno = $dwdetail->mobile;

      //=======================================
        $msg = "Thank you, your don't worry offer is approvd. Your Warranty is extended for " .$dwday.  " days";
        //self::send_msg($mobileno,$msg);

      //=======================================


      $data1['iswar'] = 1;
      $data1['isdw'] = 1;
      $data1['dwday'] = $dwday;
      $data1['dwcharge'] = $dwcharge;
      $data1['twperiod'] = $wperiod + $dwday;

    }

Smsdetail::create($data1);
//dd($data1);
//----------------------------------------
    

} 

    //Brand::create($request->all());
    
    return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }


}
