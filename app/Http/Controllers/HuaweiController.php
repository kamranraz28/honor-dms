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
use App\Retailer;
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
use App\Sr;


use App\Purchase;
use App\Sale;
use App\Preturn;


use App\Division;
use App\District;
use App\Upazila;
use App\Middistrict;
use App\Tsoupazila;



class HuaweiController extends Controller
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
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
  }

  public function DashboardView(){
     if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //=====================
      $returnCount = Preturn::where('brand_id', 2)->where('status','<=', 2)->count();
      $_SESSION['returnCount'] = $returnCount;
    //=====================

    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    $data['totalSale'] = Smsdetail::select('id')->where('brand_id',2)->count();

    $data['monthlySale'] = Smsdetail::select('id')->where('brand_id',2)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-m") )->count();

    $data['todaySale'] = Smsdetail::select('id')->where('brand_id',2)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date("Y-m-d") )->count();
     


//==================dayinmonthchartdata====================

$d=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));

  $datalist = [];
  for($d=1; $d<=31; $d++)
  {
    $time=mktime(12, 0, 0, date('m'), $d, date('Y'));          
    if (date('m', $time)==date('m')) {
      $count = Smsdetail::select('id')->where('brand_id',2)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date('Y-m-d', $time))->count();

      $dayinmonthchartdata[] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'sale' => $count];

    }      
          
  }

//dd($dayinmonthchartdata);


//==================dayinmonthchartdata====================


//==================monthinyearchartdata====================

  $marray = ['01','02','03','04','05','06','07','08','09','10','11','12'];

  foreach ($marray as $key => $marray_val) {
    
    $count = Smsdetail::select('id')->where('brand_id',2)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-$marray_val") )->count();

    $monthinyearchartdata[] = ['year' => date('Y'), 'month' => $marray_val, 'sale' => $count];
  }


  //dd($monthinyearchartdata);
//==================monthinyearchartdata====================

//==================monthlytopproductchart====================
  $monthlytopproductchart = DB::table('smsdetails as t1')
            ->select(
              DB::raw('COUNT(t1.product_id) as sale'), DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
            )
            ->where('t1.brand_id',2)
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"),date("Y-m") )
            ->groupBy('product_id')
            ->take(5)
            ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
            ->get();


  //dd($monthlytopproductchart);
//==================monthlytopproductchart====================

//==================monthlytopretailerchart====================
  $monthlytopretailerchart = DB::table('smsdetails as t1')
            ->select(
              DB::raw('COUNT(t1.user_id) as sale'), DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,15), "-", officeid)  FROM users WHERE id = t1.user_id) as user')
            )
            ->where('t1.brand_id',2)
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"),date("Y-m") )
            ->groupBy('user_id')
            ->take(5)
            ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
            ->get();


  //dd($monthlytopretailerchart);
//==================monthlytopretailerchart====================

//==================todaybrandwisesalechart====================

    $brandResult = Brand::select('name','id')->where('id',2)->get();
    $brands = $brandResult->toArray();


    foreach ($brands as $key => $value) {
      $brand_id = $value['id'];
      $name = $value['name'];

    $sale = Smsdetail::select('id')->where('brand_id',$brand_id)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date("Y-m-d") )->count();

    $todaybrandwisesalechart[] = ['name'=>$name, 'sale' => $sale];

    }


//==================todaybrandwisesalechart====================


//==================monthlybrandwisesalechart====================

    $brandResult = Brand::select('name','id')->where('id',2)->get();
    $brands = $brandResult->toArray();


    foreach ($brands as $key => $value) {
      $brand_id = $value['id'];
      $name = $value['name'];

    $sale = Smsdetail::select('id')->where('brand_id',$brand_id)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-m") )->count();

    $monthlybrandwisesalechart[] = ['name'=>$name, 'sale' => $sale];

    }


//dd($color_code_array[$index[rand(0,9)]]);

//==================monthlybrandwisesalechart====================




//==================monthlytopproductsalechart====================
  $monthlytopproductsalechart = DB::table('sales as t1')
            ->select(
              DB::raw('COUNT(t1.product_id) as sale'), 
              DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
            )
            ->where('t1.brand_id',2)
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"),date("Y-m") )
            ->groupBy('product_id')
            ->take(5)
            ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
            ->get();


  //dd($monthlytopproductsalechart);
//==================monthlytopproductsalechart====================

//==================monthlytopdistributorchart====================
  $monthlytopdistributorchart = DB::table('sales as t1')
            ->select(
              DB::raw('COUNT(t1.user_id) as sale'), 
              DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,18), "-", officeid)  FROM users WHERE id = t1.user_id) as user')
            )
            ->where('t1.brand_id',2)
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"),date("Y-m") )
            ->groupBy('user_id')
            ->take(5)
            ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
            ->get();


  //dd($monthlytopdistributorchart);


//==================================
/*$productResult = Product::with('cat','brand')->select('id','name','model','cat_id','brand_id')->where(['brand_id'=>2])->orderBy('id','desc')->get();
$products = $productResult->toArray();

$level1Data = [];
foreach ($products as $key => $productValue) {
  $product_id = $productValue['id'];
  $pdt = $productValue['name'];
  $model = $productValue['model'];

  $brand = $productValue['brand']['name'];
  $cat = $productValue['cat']['name'];

  $ndpr = Stock::where(['product_id'=>$product_id,'brand_id'=>2])->count();*/
  //$ndsl = Purchase::where(['product_id'=>$product_id,'brand_id'=>2])->count();


  /*$purchaseResult = Purchase::select(DB::raw('SUM(quantity) as quantity'))->where(['product_id'=>$product_id,'brand_id'=>2])->first();
  $purchases = $purchaseResult->toArray();
  $ndsl = $purchases['quantity'];

  $ndst = $ndpr - $ndsl;

  $dpr = $ndsl;
  $dsl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])->count();
  $dst = $dpr - $dsl;

  $tsl = Smsdetail::where(['product_id'=>$product_id,'brand_id'=>2])->count();
  $rst = $dsl-$tsl;

  $ddlysl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date("Y-m-d"),date("Y-m-d")])
                ->count();

  $dwsl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime('last sunday')),date('Y-m-d',strtotime('last sunday'.'+6 days'))])
                ->count();

  $dwsl1 = $dwsl;

  $dmsl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 month")),date("Y-m-d")])
                ->count();

  $cdlysl = Smsdetail::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date("Y-m-d"),date("Y-m-d")])
                ->count();*/

  /*$cwsl = Smsdetail::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 week")),date("Y-m-d")])
                ->count();*/

  /*$cwsl = Smsdetail::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime('last sunday')),date('Y-m-d',strtotime('last sunday'.'+6 days'))])
                ->count();

  $cwsl1 = $cwsl;    

  $cmsl = Smsdetail::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 month")),date("Y-m-d")])
                ->count();





if ($cwsl1 == 0) {
  $cdos = $rst/1;
}else{
  $cwsl1 = ceil($cwsl1/7);
  $cdos = $rst/$cwsl1;
} 


if ($cwsl1 == 0) {
  $ddos = $dst/1;
}else{
  $cwsl1 = ceil($cwsl1/7);
  $ddos = $dst/$cwsl1;
} 
*/

/*if ($dwsl1 == 0) {
  $ddos = $dst/1;
}else{

  $dwsl1 = ceil($dwsl1/7);
  $ddos = $dst/$dwsl1;
}   */  

    

 /* $level1Data[]=[
    'pdt'=>$pdt,
    'cat'=>$cat,
    'brand'=>$brand,
    'ndpr'=>$ndpr,
    'ndsl'=>$ndsl,
    'ndst'=>$ndst,
    'dpr'=>$dpr,
    'dsl'=>$dsl,
    'dst'=>$dst,
    'tsl'=>$tsl,
    'rst'=>$rst,
    'ddlysl'=>$ddlysl,
    'dwsl'=>$dwsl,
    'dmsl'=>$dmsl,
    'cdlysl'=>$cdlysl,
    'cwsl'=>$cwsl,
    'cmsl'=>$cmsl,
    'ddos'=>round($ddos),
    'cdos'=>round($cdos),
  ];

}*/
//dd($level1Data);
//==================================


//==================monthlytopdistributorchart====================

    return view('huawei.dashboard',['data' => $data, /*'level1Data'=>$level1Data,*/ 'dayinmonthchartdata'=>$dayinmonthchartdata, 'monthinyearchartdata'=>$monthinyearchartdata,'monthlytopproductchart'=>$monthlytopproductchart,'monthlytopretailerchart'=>$monthlytopretailerchart,'todaybrandwisesalechart'=>$todaybrandwisesalechart,'monthlybrandwisesalechart'=>$monthlybrandwisesalechart,
      'monthlytopproductsalechart' => $monthlytopproductsalechart, 'monthlytopdistributorchart' => $monthlytopdistributorchart]);


    
  }

  public function Test(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    return "this is retailer testing page";
  }




//================DailySalesReport=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('huawei.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->where('id',2)->orWhere('name','Huawei')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',1000)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    $userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->orderBy('id','desc')->get();
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

    if($brand_id && $fdate && $todate && !$user_id && !$sno){
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

    }elseif($brand_id && $fdate && $todate && $user_id && !$sno){
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

    }elseif($brand_id && $fdate && $todate && !$user_id && $sno){
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

    }elseif($brand_id && $fdate && $todate && $user_id && $sno){
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

    return view('huawei.dailySalesReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);

  }


  public function DailySalesReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    


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


    return redirect(route('huawei.dailySalesReport'));


  }

//================DailySalesReport======================

//================DailyCampaignReport=======================

  
  public function DailyCampaignReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->where('id',2)->orWhere('name','Huawei')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('id',Auth::id())->where('level',1000)->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    $userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->orderBy('id','desc')->get();
    $users = $userResult->toArray();

    $brand_id = Session::get('brand_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');



    $ssdata = [];
    $totalamount = [];
    $dailyCampaignReports = [];

    if($brand_id && $fdate && $todate && !$user_id && !$sno){
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

    }elseif($brand_id && $fdate && $todate && $user_id && !$sno){
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

    }elseif($brand_id && $fdate && $todate && !$user_id && $sno){
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

    }elseif($brand_id && $fdate && $todate && $user_id && $sno){
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

    return view('huawei.dailyCampaignReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailyCampaignReports'=>$dailyCampaignReports]);

  }


  public function DailyCampaignReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

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


    return redirect(route('huawei.dailyCampaignReport'));


  }

//================dailyCampaignReport======================




//================DailySalesReportWithDistrict=======================

  
  public function DailySalesReportWithDistrictView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$districtResult = District::select('id','name')->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();*/


    $districtResult = Middistrict::select('id','name','district_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',1000)->where('id',Auth::id())->orderBy('id','desc')->get();
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

    return view('huawei.dailySalesReportWithDistrict',['brands'=>$brands,
      'districts'=>$districts,'ssdata'=>$ssdata,'dailySalesReportWithDistricts'=>$dailySalesReportWithDistricts]);

  }


  public function DailySalesReportWithDistrictViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

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


    return redirect(route('huawei.dailySalesReportWithDistrict'));


  }

//================DailySalesReportWithDistrict======================





//================DailyCampaignReportWithDistrict=======================

  
  public function DailyCampaignReportWithDistrictView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$districtResult = District::select('id','name')->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();*/


    $districtResult = Middistrict::select('id','name','district_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $districts = $districtResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',1000)->where('id',Auth::id())->orderBy('id','desc')->get();
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

    return view('huawei.dailyCampaignReportWithDistrict',[
      'brands'=>$brands,'districts'=>$districts,'ssdata'=>$ssdata,
      'dailyCampaignReportWithDistricts'=>$dailyCampaignReportWithDistricts]);

  }


  public function DailyCampaignReportWithDistrictViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

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


    return redirect(route('huawei.dailyCampaignReportWithDistrict'));


  }

//================dailyCampaignReportWithDistrict======================





//================DailyRetailerStockReport=======================
  
  public function DailyRetailerStockReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    $retailerResult = User::where('level',200)->orderBy('id','desc')->get();
    $retailers = $retailerResult->toArray();



    $ssdata = [];
    $totalamount = [];
    $dailyRetailerStockReports = [];
    $ssdata['count'] = 0;


//dd(Session::all());

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    $retailer_id = Session::get('retailer_id');
    $all_report = Session::get('all_report');

    if (!$fdate && !$todate && $all_report && $retailer_id) {
//--------------------------------------------------


//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
    $productResult = Product::select('id','name','model')->where(['brand_id'=>2])->orderBy('id','desc')->get();
    $products = $productResult->toArray();


foreach ($products as $key => $product1) {
  $product_id = $product1['id'];
  $product = $product1['name'];
  $model = $product1['model'];



if ($retailer_id == "All") {

// with retailer_id========
$pcount = Sale::where('product_id',$product_id)->count();

if ($pcount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('product_id',$product_id)->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();

  //$retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $retailer = "All Retailers";
  $sin = $Sales['sin'];
} else {
  $retailer = "All Retailers";
  $sin = 0;
}

$scount = Smsdetail::where('product_id',$product_id)->count();

if ($scount > 0 ) {
  $SmsdetailResult = Smsdetail::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('product_id',$product_id)->groupBy('product_id')->first();
  $Smsdetails = $SmsdetailResult->toArray();

  //$retailer = $Smsdetails['user']['firstname'] . " - ". $Smsdetails['user']['officeid'];
  $retailer = "All Retailers";
  $sout = $Smsdetails['sout'];
} else {
  $retailer = "All Retailers";
  $sout = 0;
}

// with retailer_id========
} else {

// with retailer_id========
$pcount = Sale::where('ruser_id',$retailer_id)->where('product_id',$product_id)->count();

if ($pcount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('ruser_id',$retailer_id)->where('product_id',$product_id)->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();

  $retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $sin = $Sales['sin'];
} else {
  //$retailer = " - ";
  $sin = 0;
  $userData = User::where('id',$retailer_id)->first();
  $retailer = $userData->firstname . " " . $userData->officeid;
}

$scount = Smsdetail::where('user_id',$retailer_id)->where('product_id',$product_id)->count();

if ($scount > 0 ) {
  $SmsdetailResult = Smsdetail::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('user_id',$retailer_id)->where('product_id',$product_id)->groupBy('product_id')->first();
  $Smsdetails = $SmsdetailResult->toArray();

  $retailer = $Smsdetails['user']['firstname'] . " - ". $Smsdetails['user']['officeid'];
  $sout = $Smsdetails['sout'];
} else {
  //$retailer = " - ";
  $sout = 0;
  $userData = User::where('id',$retailer_id)->first();
  $retailer = $userData->firstname . " " . $userData->officeid;
}

// with retailer_id========
}





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
//--------------------------------------------------
    }elseif($fdate && $todate && !$all_report  && $retailer_id){
//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
    
//--------------------------------------------------

    $productResult = Product::select('id','name','model')->where(['brand_id'=>2])->orderBy('id','desc')->get();
    $products = $productResult->toArray();


foreach ($products as $key => $product1) {
  $product_id = $product1['id'];
  $product = $product1['name'];
  $model = $product1['model'];


if ($retailer_id == "All") {

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

} else {

// with retailer_id========

$pcount = Sale::where('ruser_id',$retailer_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($pcount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('ruser_id',$retailer_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();
  $retailer = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $sin = $Sales['sin'];
} else {
  //$retailer = " - ";
  $sin = 0;

  $userData = User::where('id',$retailer_id)->first();
  $retailer = $userData->firstname . " " . $userData->officeid;
}

$scount = Smsdetail::where('user_id',$retailer_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($scount > 0 ) {
  $SmsdetailResult = Smsdetail::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('user_id',$retailer_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Smsdetails = $SmsdetailResult->toArray();
  $retailer = $Smsdetails['user']['firstname'] . " - ". $Smsdetails['user']['officeid'];
  $sout = $Smsdetails['sout'];
} else {
  //$retailer = " - ";
  $sout = 0;
  $userData = User::where('id',$retailer_id)->first();
  $retailer = $userData->firstname . " " . $userData->officeid;
}

// with retailer_id========

}


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



}


//Session::forget(['user_id','fdate','todate']);

    return view('huawei.dailyRetailerStockReport',['ssdata'=>$ssdata,'dailyRetailerStockReports'=>$dailyRetailerStockReports,'retailers' => $retailers]);

  }


  public function DailyRetailerStockReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['fdate','todate','all_report']);


if ($request->get('all_report') == null) {
    
    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'retailer_id' => 'required',
    ]);


    $retailer_id = $request->get('retailer_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');

    Session::put(['fdate'=>$fdate,'todate'=>$todate,'retailer_id'=>$retailer_id]);
}else{
    $this->validate($request, [
      'all_report' => 'required','retailer_id' => 'required',
    ]);
    $retailer_id = $request->get('retailer_id');
    $all_report = $request->get('all_report');

    Session::put(['all_report'=>$all_report,'retailer_id'=>$retailer_id ]);
}

  return redirect(route('huawei.dailyRetailerStockReport'));


  }

//================DailyRetailerStockReport=======================




//DailyStockReport=======================
  
  public function DailyStockReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    $distributorResult = User::where('level',100)->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();



    $ssdata = [];
    $totalamount = [];
    $dailyStockReports = [];
    $ssdata['count'] = 0;


//dd(Session::all());

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    $distributor_id = Session::get('distributor_id');
    $all_report = Session::get('all_report');

    if (!$fdate && !$todate && $all_report && $distributor_id) {
//--------------------------------------------------


//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
    $productResult = Product::select('id','name','model')->where(['brand_id'=>2])->orderBy('id','desc')->get();
    $products = $productResult->toArray();


foreach ($products as $key => $product1) {
  $product_id = $product1['id'];
  $product = $product1['name'];
  $model = $product1['model'];



if ($distributor_id == "All") {

// with distributor_id========
$pcount = Purchase::where('product_id',$product_id)->count();

if ($pcount > 0 ) {
  $PurchaseResult = Purchase::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('product_id',$product_id)->groupBy('product_id')->first();
  $Purchases = $PurchaseResult->toArray();

  //$distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
  $distributor = "All Distributoirs";
  $sin = $Purchases['sin'];
} else {
  $distributor = "All Distributoirs";
  $sin = 0;
}

$scount = Sale::where('product_id',$product_id)->count();

if ($scount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('product_id',$product_id)->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();

  //$distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $distributor = "All Distributoirs";
  $sout = $Sales['sout'];
} else {
  $distributor = "All Distributoirs";
  $sout = 0;
}

// with distributor_id========
} else {

// with distributor_id========
$pcount = Purchase::where('user_id',$distributor_id)->where('product_id',$product_id)->count();

if ($pcount > 0 ) {
  $PurchaseResult = Purchase::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('user_id',$distributor_id)->where('product_id',$product_id)->groupBy('product_id')->first();
  $Purchases = $PurchaseResult->toArray();

  $distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
  $sin = $Purchases['sin'];
} else {
  //$distributor = " - ";
  $sin = 0;
  $userData = User::where('id',$distributor_id)->first();
  $distributor = $userData->firstname . " " . $userData->officeid;
}

$scount = Sale::where('user_id',$distributor_id)->where('product_id',$product_id)->count();

if ($scount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('user_id',$distributor_id)->where('product_id',$product_id)->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();

  $distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $sout = $Sales['sout'];
} else {
  //$distributor = " - ";
  $sout = 0;
  $userData = User::where('id',$distributor_id)->first();
  $distributor = $userData->firstname . " " . $userData->officeid;
}

// with distributor_id========
}





  $dailyStockReports[] = [
    'distributor' => $distributor,
    'product_id' => $product_id,
    'product' => $product,
    'model' => $model,
    'stockin' => $sin,
    'stockout' => $sout,
    'stock' => $sin - $sout
  ]; 



}
//--------------------------------------------------
    }elseif($fdate && $todate && !$all_report  && $distributor_id){
//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
    
//--------------------------------------------------

    $productResult = Product::select('id','name','model')->where(['brand_id'=>2])->orderBy('id','desc')->get();
    $products = $productResult->toArray();


foreach ($products as $key => $product1) {
  $product_id = $product1['id'];
  $product = $product1['name'];
  $model = $product1['model'];


if ($distributor_id == "All") {

// with distributor_id all========

$pcount = Purchase::where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($pcount > 0 ) {
  $PurchaseResult = Purchase::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Purchases = $PurchaseResult->toArray();
  //$distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
  $distributor = "All Distributoirs";
  $sin = $Purchases['sin'];
} else {
  $distributor = "All Distributoirs";
  $sin = 0;
}

$scount = Sale::where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($scount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();
  //$distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $distributor = "All Distributoirs";
  $sout = $Sales['sout'];
} else {
  $distributor = "All Distributoirs";
  $sout = 0;
}

// with distributor_id all========

} else {

// with distributor_id========

$pcount = Purchase::where('user_id',$distributor_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($pcount > 0 ) {
  $PurchaseResult = Purchase::with('user')->select('user_id',DB::raw('SUM(quantity) AS sin'))->where('user_id',$distributor_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Purchases = $PurchaseResult->toArray();
  $distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
  $sin = $Purchases['sin'];
} else {
  //$distributor = " - ";
  $sin = 0;

  $userData = User::where('id',$distributor_id)->first();
  $distributor = $userData->firstname . " " . $userData->officeid;
}

$scount = Sale::where('user_id',$distributor_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->count();

if ($scount > 0 ) {
  $SaleResult = Sale::with('user')->select('user_id',DB::raw('COUNT(product_id) as sout'))->where('user_id',$distributor_id)->where('product_id',$product_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->first();
  $Sales = $SaleResult->toArray();
  $distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
  $sout = $Sales['sout'];
} else {
  //$distributor = " - ";
  $sout = 0;
  $userData = User::where('id',$distributor_id)->first();
  $distributor = $userData->firstname . " " . $userData->officeid;
}

// with distributor_id========

}


  $dailyStockReports[] = [
    'distributor' => $distributor,
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

    return view('huawei.dailyStockReport',['ssdata'=>$ssdata,'dailyStockReports'=>$dailyStockReports,'distributors' => $distributors]);

  }


  public function DailyStockReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['fdate','todate','all_report']);


if ($request->get('all_report') == null) {
    
    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'distributor_id' => 'required',
    ]);


    $distributor_id = $request->get('distributor_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');

    Session::put(['fdate'=>$fdate,'todate'=>$todate,'distributor_id'=>$distributor_id]);
}else{
    $this->validate($request, [
      'all_report' => 'required','distributor_id' => 'required',
    ]);
    $distributor_id = $request->get('distributor_id');
    $all_report = $request->get('all_report');

    Session::put(['all_report'=>$all_report,'distributor_id'=>$distributor_id ]);
}

  return redirect(route('huawei.dailyStockReport'));


  }

//DailyStockReport=======================





//================DailyPurchaseSaleReport=======================
  
  public function DailyPurchaseSaleReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    $distributorResult = User::where('level',100)->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();

    $productResult = Product::where(['brand_id'=>2])->orderBy('id','desc')->get();
    $products = $productResult->toArray();

    $retailerResult = Retailer::orderBy('id','desc')->get();
    $retailers = $retailerResult->toArray();

    $ssdata = [];
    $totalamount = [];
    $dailyPurchaseSaleReports = [];
    $ssdata['count'] = 0;

    $purchases = [];
    $sales = [];


//dd(Session::all());

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    $type = Session::get('type');
    $distributor_id = Session::get('distributor_id');


  if($fdate && $todate && $type && $distributor_id){
//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['type'] = $type;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
   
   if ($distributor_id == "All") {
      //with distributor_id all===========
        if ($type == "Purchase") {
          $purchases = Purchase::with('product','user')->select('id','user_id','product_id','quantity','sno','imei','created_at')->where('brand_id',2)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(1000);
        } elseif($type == "Sale") {
          $sales = Sale::with('product','retailer','user')->select('id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(1000);
        }
      //with distributor_id all===========
    } else {
      //with distributor_id===========
        if ($type == "Purchase") {
          $purchases = Purchase::with('product','user')->select('id','user_id','product_id','quantity','sno','imei','created_at')->where('brand_id',2)->where('user_id',$distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(1000);
        } elseif($type == "Sale") {
          $sales = Sale::with('product','retailer','user')->select('id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->where('user_id',$distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(1000);
        }
      //with distributor_id===========
    }
     





}


//Session::forget(['user_id','fdate','todate']);

    return view('huawei.dailyPurchaseSaleReport',['ssdata'=>$ssdata,'dailyPurchaseSaleReports'=>$dailyPurchaseSaleReports,'distributors' => $distributors,'sales'=>$sales,'purchases'=>$purchases,'retailers'=>$retailers,'products'=>$products]);

  }


  public function DailyPurchaseSaleReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['fdate','todate','all_report']);


    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'distributor_id' => 'required',
      'type' => 'required',
    ]);


    $distributor_id = $request->get('distributor_id');
    $type = $request->get('type');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');

    Session::put(['fdate'=>$fdate,'todate'=>$todate,'type'=>$type,'distributor_id'=>$distributor_id]);

    return redirect(route('huawei.dailyPurchaseSaleReport'));


  }


//================DailyPurchaseSaleReport=======================


//dailyDistributorSalesReport======================
  
  public function DailyDistributorSalesReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->where(['id'=>2])->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    //$userResult = User::select('dist_return')->where('level',100)->where('id',Auth::id())->first();
    //$status = $userResult->toArray();



    $distributorResult = User::select('id','firstname','officeid')->where('level',100)->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();


    //$retailerResult = Retailer::select('id as id','name as name','officeid','retailer_id')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    //$retailers = $retailerResult->toArray();


    $distributor_id = Session::get('distributor_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


    /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(1000);
    //$sales = $saleResult->toArray();*/

    $ssdata = [];
    $totalamount = [];
    $dailyDistributorSalesReports = [];

    if ($distributor_id == 'all' && $fdate && $todate && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;


        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);

    }elseif($distributor_id != 'all' && $fdate && $todate && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;
        

        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->where(['user_id'=>$distributor_id])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


    }elseif($distributor_id == 'all' && $fdate && $todate && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;

        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->where(['sno'=>$sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


    }elseif($distributor_id != 'all' && $fdate && $todate && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;


        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where('brand_id',2)->where(['user_id'=>$distributor_id,'sno'=>$sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);



    }else{
      $dailyDistributorSalesReports = [];
    }
//dd($dailyDistributorSalesReports);

//Session::forget(['retailer_id','user_id','sno','fdate','todate']);

//dd(Auth::id());


    return view('huawei.dailydistributorSalesReport',['brands'=>$brands,'distributors'=>$distributors,'ssdata'=>$ssdata,'dailyDistributorSalesReports'=>$dailyDistributorSalesReports]);

  }


  public function DailyDistributorSalesReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    


    Session::forget(['distributor_id','sno','fdate','todate']);

    $this->validate($request, [
      'distributor_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $distributor_id = $request->get('distributor_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['distributor_id'=>$distributor_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('huawei.dailyDistributorSalesReport'));


  }

//dailyDistributorSalesReport======================



//================RetailerCheckReportView=======================
  
  public function RetailerCheckReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    $distributorResult = User::where('level',100)->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();



    $ssdata = [];
    $retailerCheckReports = [];
    $ssdata['count'] = 0;

    $distributor_id = Session::get('distributor_id');

//dd($distributor_id);
    if ($distributor_id == "All") {
  //--------------------------------------------------
      $distributorResult = User::with('retailer')->select('id','firstname','email','officeid')->where('level',100)->orderBy('id','desc')->get();
      $retailerCheckReports = $distributorResult->toArray();

    }else{
      $distributorResult = User::with('retailer')->select('id','firstname','email','officeid')->where('id',$distributor_id)->where('level',100)->orderBy('id','desc')->get();
      $retailerCheckReports = $distributorResult->toArray();

    }

//dd($retailerCheckReports);
//Session::forget(['user_id','fdate','todate']);

    return view('huawei.retailerCheckReport',['ssdata'=>$ssdata,'retailerCheckReports'=>$retailerCheckReports,'distributors' => $distributors]);

  }


  public function RetailerCheckReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //dd($request->all());
    Session::forget(['distributor_id']);

    $this->validate($request, [
      'distributor_id' => 'required',
    ]);
    $distributor_id = $request->get('distributor_id');

    Session::put(['distributor_id'=>$distributor_id]);


    return redirect(route('huawei.retailerCheckReport'));


  }

//================RetailerCheckReportView=======================

















//================WcheckProduct=======================

  
  public function WcheckProductViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('huawei.wcheckProducts_print',['ssdata'=>$ssdata,'wcheckProducts'=>$wcheckProducts,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userwcheckProducts.pdf');

  }


  
  public function WcheckProductView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    
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

    return view('huawei.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('huawei.wcheckProduct'));


  }

//================WcheckProduct=======================





// Huawei =======================================
  
  public function HuaweiView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    //$userCount = User::count();
    
    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where('id',Auth::id())->where('level',1000)->orderBy('id','desc')->paginate(1000);
    //$users = $userResult->toArray();

//dd($users);

    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('huawei.huawei',['users'=>$userResult,
    'divisions'=>$divisions, 'districts'=>$districts,'upazilas'=>$upazilas ]);

  }


  public function HuaweiUpdate(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
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
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}    
    
    //dd($request->all());

    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        'password' => 'required|min:3|max:1000',
        'confirm_password' => 'required|min:3|max:1000|same:password',
      ]); 

      $user->password = bcrypt($request['password']);
      $user->save();

    }

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }




// Huawei =======================================


  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}


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
  
  public function ReturnProductViewAll(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
  
    //=====================
      $returnCount = Preturn::where('brand_id', 2)->where('status','<=', 2)->count();
      $_SESSION['returnCount'] = $returnCount;
    //=====================

    $preturns = Preturn::with('product')->select('id','product_id','retailer_id','sno','imei','created_at','updated_at','status',DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = user_id) as distributor'),DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))->OrderBy('id','Desc')->paginate(300);


    //dd($preturns);
    

    return view('huawei.returnProductAll',['preturns'=>$preturns]);

  }

// ReturnProduct =======================================


//================DailyReturnReport======================

  public function DailyReturnReportView(){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}
    
    //Session::forget(['fdate','todate']);

    //=====================
      $returnCount = Preturn::where('brand_id', 2)->where('status','<=', 2)->count();
      $_SESSION['returnCount'] = $returnCount;
    //=====================

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

    $ssdata = [];
    $preturns = [];

    if ($fdate && $todate) {

      $ssdata['fdate'] = $fdate;
      $ssdata['todate'] = $todate;


      $preturns = Preturn::with('product')

                ->select('id','product_id','retailer_id','sno','imei','created_at','updated_at','status',
                  DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = user_id) as distributor'),

                  DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))
                ->where('brand_id', 2)
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                ->OrderBy('status','Desc')
                ->OrderBy('id','Desc')
                ->get();


      //dd($preturns);
    }



    return view('huawei.dailyReturnReport',['ssdata'=>$ssdata,'preturns'=>$preturns]);

  }


  public function DailyReturnReportViewStore(Request $request){
    if (Auth::user()->level != 1000) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['fdate','todate']);

    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('huawei.dailyReturnReport'));


  }

//================DailyReturnReport======================






}
