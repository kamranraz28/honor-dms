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
use App\Retailer;


use App\Purchase;
use App\Sale;

use App\Division;
use App\District;
use App\Upazila;
use App\Middistrict;
use App\Tsoupazila;



class MidmanagementController extends Controller
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
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
  }

  public function DashboardView(){
     if (Auth::user()->level != 300) { return redirect()->route('logout');}



    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    $data['totalSale'] = Smsdetail::select('id')->where('user_id',Auth::id())->count();

    $data['monthlySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-m") )->count();
    
    $data['todaySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date("Y-m-d") )->count();
     
    return view('midmanagement.dashboard',['data' => $data]);
    
  }

  public function Test(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    return "this is retailer testing page";
  }




//================DailySalesReport=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('midmanagement.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',300)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    $userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();



//dd($users);

$brand_id = Session::get('brand_id');
    $user_id = Session::get('user_id');
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
          ->where(['user_id'=>$user_id])
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
          ->where(['user_id'=>$user_id,'sno'=>$sno])
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
          ->where(['brand_id'=>$brand_id,'user_id'=>$user_id])
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
          ->where(['brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno])
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();

    }else{
      $dailySalesReports = [];
    }
//dd($dailySalesReports);

//Session::forget(['brand_id','user_id','sno','fdate','todate']);

    return view('midmanagement.dailySalesReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);

  }


  public function DailySalesReportViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    


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


    return redirect(route('midmanagement.dailySalesReport'));


  }

//================DailySalesReport======================

//================DailyCampaignReport=======================

  
  public function DailyCampaignReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('midmanagement.dailyCampaignReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailyCampaignReportView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('id',Auth::id())->where('level',300)->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    $userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
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
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;

        $dailyCampaignReports = Smsdetail::with('user','brand','product','promodetail')
          ->select('id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
            DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
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

    return view('midmanagement.dailyCampaignReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailyCampaignReports'=>$dailyCampaignReports]);

  }


  public function DailyCampaignReportViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

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


    return redirect(route('midmanagement.dailyCampaignReport'));


  }

//================dailyCampaignReport======================





//================DailyRetailerStockReportForDistrict=======================

  
  
  public function DailyRetailerStockReportForDistrictView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

 


    $districtResult = Middistrict::select('id','name','district_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();

//dd($users);

    $district_id = Session::get('district_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();
  foreach ($usersdatas as $key => $value) {
    $user_id = $value['id'];
    $datar['user_id'][] = $user_id;
  }

}

dd($datar);*/

$ssdata = [];
$totalamount = [];
$dailyRetailerStockReports = [];


if ($district_id) {
  # code...
$ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id;

$productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
$products = $productResult->toArray();

foreach ($products as $key => $productValue) {
  $product_id = $productValue['id'];
  $product = $productValue['name'];
  $model = $productValue['model'];

//========================
if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();
  foreach ($usersdatas as $key => $value) {
    //$user_id = $value['id'];
//===============
// with retailer_id all========

$pcount = Sale::where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($pcount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();
  //$retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $retailer = "All Retailers";
  $sin = $Sales['sin'];
} else {
  $retailer = "All Retailers";
  $sin = 0;
}

$scount = Smsdetail::where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($scount > 0 ) {
  $SmsdetailResult = Smsdetail::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Smsdetails = $SmsdetailResult->toArray();
  //$retailer = $Smsdetails['user']['firstname'] . " - ". $Smsdetails['user']['officeid'];
  $retailer = "All Retailers";
  $sout = $Smsdetails['sout'];
} else {
  $retailer = "All Retailers";
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



}else{

// with user_id========

$pcount = Sale::where('ruser_id',$user_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($pcount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('ruser_id',$user_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();
  $retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $sin = $Sales['sin'];
} else {
  //$retailer = " - ";
  $sin = 0;

  $userData = User::where('id',$user_id)->first();
  $retailer = $userData->firstname . " " . $userData->officeid;
}

$scount = Smsdetail::where('user_id',$user_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($scount > 0 ) {
  $SmsdetailResult = Smsdetail::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('user_id',$user_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Smsdetails = $SmsdetailResult->toArray();
  $retailer = $Smsdetails['user']['firstname'] . " - ". $Smsdetails['user']['officeid'];
  $sout = $Smsdetails['sout'];
} else {
  //$retailer = " - ";
  $sout = 0;
  $userData = User::where('id',$user_id)->first();
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

//Session::forget(['district_id','brand_id','user_id','sno','fdate','todate']);

    return view('midmanagement.dailyRetailerStockReportForDistrict',[
      'districts'=>$districts,'ssdata'=>$ssdata,'dailyRetailerStockReports'=>$dailyRetailerStockReports]);

  }


  public function DailyRetailerStockReportForDistrictViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['district_id','user_id','fdate','todate']);

    $this->validate($request, [
      'district_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $district_id = $request->get('district_id');
    $user_id = $request->get('user_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['district_id'=>$district_id,'user_id'=>$user_id,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('midmanagement.dailyRetailerStockReportForDistrict'));


  }

//================DailyRetailerStockReportForDistrict======================











//================DailySalesReportWithDistrict=======================

  
  public function DailySalesReportWithDistrictViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('midmanagement.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportWithDistrictView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$districtResult = District::select('id','name')->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();*/


    $districtResult = Middistrict::select('id','name','district_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',300)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    /*$userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/



//dd($users);

    $district_id = Session::get('district_id');
    $brand_id = Session::get('brand_id');
    //$retailer_id = Session::get('retailer_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();

  foreach ($usersdatas as $key => $value) {
    $user_id = $value['id'];

    $datar['user_id'][] = $user_id;

  }


}

dd($datar);*/


$datar = [];
    $ssdata = [];
    $totalamount = [];
    $dailySalesReportWithDistricts = [];




if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();

  foreach ($usersdatas as $key => $value) {
    $user_id = $value['id'];

    if ($brand_id == 'all' && $user_id && $fdate && $todate  && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id])->count();
      if ($count > 0) {
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==  
      }
    }elseif ($brand_id == 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'sno' => $sno])->count();
      if ($count > 0) {
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'sno' => $sno])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==  
      }
    }elseif ($brand_id != 'all' && $user_id && $fdate && $todate  && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'brand_id' => $brand_id])->count();
      if ($count > 0) {
      //== 
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==
      }
    }elseif ($brand_id != 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno ])->count();
      if ($count > 0) {
        
      //====
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno ])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //===

      }
    }
//end of foreach
  }
//dd($dailySalesReportWithDistricts);
}else{
  if ($brand_id == 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;

      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'sno' => $sno])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==  

    }elseif($brand_id == 'all' && $user_id && $fdate && $todate && !$sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==  
    }elseif($brand_id != 'all' && $user_id && $fdate && $todate && !$sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //== 
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //==

    }elseif($brand_id != 'all' && $user_id && $fdate && $todate && $sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //====
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno ])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailySalesReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt']
          ];
        }
      //===

    }
}


//dd($dailySalesReportWithDistricts);

//Session::forget(['district_id','brand_id','user_id','sno','fdate','todate']);

    return view('midmanagement.dailySalesReportWithDistrict',['brands'=>$brands,
      'districts'=>$districts,'ssdata'=>$ssdata,'dailySalesReportWithDistricts'=>$dailySalesReportWithDistricts]);

  }


  public function DailySalesReportWithDistrictViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['district_id','brand_id','user_id','sno','fdate','todate']);

    $this->validate($request, [
      'district_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $district_id = $request->get('district_id');
    $brand_id = $request->get('brand_id');
    $user_id = $request->get('user_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['district_id'=>$district_id,'brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('midmanagement.dailySalesReportWithDistrict'));


  }

//================DailySalesReportWithDistrict======================





//================DailyCampaignReportWithDistrict=======================

  
  public function DailyCampaignReportWithDistrictView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$districtResult = District::select('id','name')->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();*/


    $districtResult = Middistrict::select('id','name','district_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',300)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    /*$userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/



//dd($users);

    $district_id = Session::get('district_id');
    $brand_id = Session::get('brand_id');
    //$retailer_id = Session::get('retailer_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();

  foreach ($usersdatas as $key => $value) {
    $user_id = $value['id'];

    $datar['user_id'][] = $user_id;

  }


}*/

//dd($datar);




//$datar = [];
    $ssdata = [];
    $totalamount = [];
    $dailyCampaignReportWithDistricts = [];




if ($user_id == 'all') {
  $userResult = User::select('id')->where(['district_id'=>$district_id,'level'=>200])->get();
  $usersdatas = $userResult->toArray();
//dd($usersdatas);
  foreach ($usersdatas as $key => $value) {
    $user_id = $value['id'];

    if ($brand_id == 'all' && $user_id && $fdate && $todate  && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id,'status'=>1])->count();
      if ($count > 0) {
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==  
      }
    }elseif ($brand_id == 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'sno' => $sno,'status'=>1])->count();
      if ($count > 0) {
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'sno' => $sno])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==  
      }
    }elseif ($brand_id != 'all' && $user_id && $fdate && $todate  && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'brand_id' => $brand_id,'status'=>1])->count();
      if ($count > 0) {
      //== 
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==
      }
    }elseif ($brand_id != 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;


      $count = Smsdetail::where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno,'status'=>1 ])->count();
      if ($count > 0) {
        
      //====
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno ])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //===

      }
    }
//end of foreach
  }
//dd($dailyCampaignReportWithDistricts);
}else{
  if ($brand_id == 'all' && $user_id && $fdate && $todate  && $sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;

      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'sno' => $sno])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==  

    }elseif($brand_id == 'all' && $user_id && $fdate && $todate && !$sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //==  
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==  
    }elseif($brand_id != 'all' && $user_id && $fdate && $todate && !$sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //== 
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //==

    }elseif($brand_id != 'all' && $user_id && $fdate && $todate && $sno){
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['user_id'] = $user_id; $ssdata['sno'] = $sno; $ssdata['brand_id'] = $brand_id;
      //====
        $query = Smsdetail::with('user','brand','product','promodetail')
        ->select('id','user_id','brand_id','product_id','promo_id','promodetail_id','user_id','mobile','imei','sno','wperiod','remarks',
          DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
          DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
          DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))
        ->where(['status'=>1])
        ->where(['user_id'=>$user_id, 'brand_id' => $brand_id, 'sno'=> $sno ])
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get();

        $smsdetailDatas = json_decode(json_encode($query), True);

        foreach ($smsdetailDatas as $key => $smsdetailData) {
          $dailyCampaignReportWithDistricts[] = [
            'user_id'=> $smsdetailData['user_id'],
            'brnadname'=> $smsdetailData['brand']['name'],
            'productname'=> $smsdetailData['product']['name'],
            'productmodel'=> $smsdetailData['product']['model'],
            'imei'=> $smsdetailData['imei'],
            'sno'=> $smsdetailData['sno'],
            'wperiod'=> $smsdetailData['wperiod'],
            'saledate'=> $smsdetailData['saledate'],
            'sdate'=> $smsdetailData['sdate'],
            'edate'=> $smsdetailData['edate'],
            'officeid'=> $smsdetailData['user']['officeid'],
            'firstname'=> $smsdetailData['user']['firstname'],
            'mobile'=> $smsdetailData['mobile'],
            'createdAt'=> $smsdetailData['createdAt'],
            'remarks'=> $smsdetailData['remarks'],
            'details'=> $smsdetailData['promodetail']['details'],
          ];
        }
      //===

    }
}



//dd($dailyCampaignReportWithDistricts);

//Session::forget(['district_id','brand_id','user_id','sno','fdate','todate']);

    return view('midmanagement.dailyCampaignReportWithDistrict',[
      'brands'=>$brands,'districts'=>$districts,'ssdata'=>$ssdata,
      'dailyCampaignReportWithDistricts'=>$dailyCampaignReportWithDistricts]);

  }


  public function DailyCampaignReportWithDistrictViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    //dd($request->all());


   

    Session::forget(['district_id','brand_id','user_id','sno','fdate','todate']);

    $this->validate($request, [
      'district_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $district_id = $request->get('district_id');
    $brand_id = $request->get('brand_id');
    $user_id = $request->get('user_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['district_id'=>$district_id,'brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    return redirect(route('midmanagement.dailyCampaignReportWithDistrict'));


  }

//================dailyCampaignReportWithDistrict======================









//================WcheckProduct=======================

  
  public function WcheckProductViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('midmanagement.wcheckProducts_print',['ssdata'=>$ssdata,'wcheckProducts'=>$wcheckProducts,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userwcheckProducts.pdf');

  }


  
  public function WcheckProductView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    
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
        
        $query = Smsdetail::with('product','replace')->select('id','product_id','promo_id','promodetail_id','sno','imei','wperiod','iswar',
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

    return view('midmanagement.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('midmanagement.wcheckProduct'));


  }

//================WcheckProduct=======================





// Midmanagement =======================================
  
  public function MidmanagementView(){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
    //$userCount = User::count();
    
    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where('id',Auth::id())->where('level',300)->orderBy('id','desc')->paginate(300);
    //$users = $userResult->toArray();

//dd($users);

    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('midmanagement.midmanagement',['users'=>$userResult,
    'divisions'=>$divisions, 'districts'=>$districts,'upazilas'=>$upazilas ]);

  }


  public function MidmanagementUpdate(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}
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
    if (Auth::user()->level != 300) { return redirect()->route('logout');}    
    
    //dd($request->all());

    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        'password' => 'required|min:3|max:300',
        'confirm_password' => 'required|min:3|max:300|same:password',
      ]); 

      $user->password = bcrypt($request['password']);
      $user->save();

    }

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }




// Midmanagement =======================================


  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 300) { return redirect()->route('logout');}


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



}
