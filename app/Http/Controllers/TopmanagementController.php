<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
//use App\Http\Requests\ClassName;
//use App\Http\Controllers\AuthController;

use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;
use Illuminate\Support\Facades\Cache;

use Rap2hpoutre\FastExcel\FastExcel;
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


class TopmanagementController extends Controller
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
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
  }

//   public function DashboardView()
//   {
//     if (Auth::user()->level != 400) {
//       return redirect()->route('logout');
//     }

//     //dd(sha1('123123'));


//     //=====================
//     $requestRetailerCount = User::where(['active' => 0, 'status' => 0, 'level' => 200])->orderBy('id', 'desc')->count();

//     $_SESSION['requestRetailerCount'] = $requestRetailerCount;

//     //=====================
// //=====================
//     $returnCount = Preturn::where('status', '<=', 2)->count();
//     $_SESSION['returnCount'] = $returnCount;
//     //=====================



//     $_SESSION['favicon'] = self::$favicon;
//     $_SESSION['logo'] = self::$logo;


//     $data['totalSale'] = Smsdetail::select('id')->count();
//     $data['monthlySale'] = Smsdetail::select('id')->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
//     $data['todaySale'] = Smsdetail::select('id')->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();


//     //==================dayinmonthchartdata====================

//     $d = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

//     $datalist = [];
//     for ($d = 1; $d <= 31; $d++) {
//       $time = mktime(12, 0, 0, date('m'), $d, date('Y'));
//       if (date('m', $time) == date('m')) {
//         $count = Smsdetail::select('id')->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date('Y-m-d', $time))->count();

//         $dayinmonthchartdata[] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'sale' => $count];

//       }

//     }

//     //dd($dayinmonthchartdata);


//     //==================dayinmonthchartdata====================


//     //==================monthinyearchartdata====================

//     $marray = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

//     foreach ($marray as $key => $marray_val) {

//       $count = Smsdetail::select('id')->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-$marray_val"))->count();

//       $monthinyearchartdata[] = ['year' => date('Y'), 'month' => $marray_val, 'sale' => $count];
//     }


//     //dd($monthinyearchartdata);
// //==================monthinyearchartdata====================

//     //==================monthlytopproductchart====================
//     $monthlytopproductchart = DB::table('smsdetails as t1')
//       ->select(
//         DB::raw('COUNT(t1.product_id) as sale'),
//         DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
//       )
//       ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
//       ->groupBy('product_id')
//       ->take(5)
//       ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
//       ->get();


//     //dd($monthlytopproductchart);
// //==================monthlytopproductchart====================

//     //==================monthlytopretailerchart====================
//     $monthlytopretailerchart = DB::table('smsdetails as t1')
//       ->select(
//         DB::raw('COUNT(t1.user_id) as sale'),
//         DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,15), "-", officeid)  FROM users WHERE id = t1.user_id) as user')
//       )
//       ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
//       ->groupBy('user_id')
//       ->take(5)
//       ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
//       ->get();


//     //dd($monthlytopretailerchart);
// //==================monthlytopretailerchart====================

//     //==================todaybrandwisesalechart====================

//     $brandResult = Brand::select('name', 'id')->get();
//     $brands = $brandResult->toArray();


//     foreach ($brands as $key => $value) {
//       $brand_id = $value['id'];
//       $name = $value['name'];

//       $sale = Smsdetail::select('id')->where('brand_id', $brand_id)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();

//       $todaybrandwisesalechart[] = ['name' => $name, 'sale' => $sale];

//     }


//     //==================todaybrandwisesalechart====================


//     //==================monthlybrandwisesalechart====================

//     $brandResult = Brand::select('name', 'id')->get();
//     $brands = $brandResult->toArray();


//     foreach ($brands as $key => $value) {
//       $brand_id = $value['id'];
//       $name = $value['name'];

//       $sale = Smsdetail::select('id')->where('brand_id', $brand_id)->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();

//       $monthlybrandwisesalechart[] = ['name' => $name, 'sale' => $sale];

//     }


//     //dd($color_code_array[$index[rand(0,9)]]);

//     //==================monthlybrandwisesalechart====================




//     //==================monthlytopproductsalechart====================
//     $monthlytopproductsalechart = DB::table('sales as t1')
//       ->select(
//         DB::raw('COUNT(t1.product_id) as sale'),
//         DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
//       )
//       ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
//       ->groupBy('product_id')
//       ->take(5)
//       ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
//       ->get();


//     //dd($monthlytopproductsalechart);
// //==================monthlytopproductsalechart====================

//     //==================monthlytopdistributorchart====================
//     $monthlytopdistributorchart = DB::table('sales as t1')
//       ->select(
//         DB::raw('COUNT(t1.user_id) as sale'),
//         DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,18), "-", officeid)  FROM users WHERE id = t1.user_id) as user')
//       )
//       ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
//       ->groupBy('user_id')
//       ->take(5)
//       ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
//       ->get();


//     //dd($monthlytopdistributorchart);
// //==================monthlytopdistributorchart====================




//     //==================================

//     /*$level1Data = [];

//     //===================================================================


//     $productResult = Product::with('cat','brand')->select('id','name','model','cat_id','brand_id')->orderBy('id','desc')->get();
//     $products = $productResult->toArray();
//     //dd($products);

//     $level1Data = [];
//     foreach ($products as $key => $productValue) {
//       $product_id = $productValue['id'];
//       $pdt = $productValue['name'];
//       $model = $productValue['model'];
//       $brand = $productValue['brand']['name'];
//       $cat = $productValue['cat']['name'];


//       $ndpr = Stock::where(['product_id'=>$product_id])->count();

//       $purchaseResult = Purchase::select(DB::raw('SUM(quantity) as quantity'))->where(['product_id'=>$product_id])->first();
//       $purchases = $purchaseResult->toArray();
      
//       $ndsl = $purchases['quantity'];

//       $ndst = $ndpr - $ndsl;

//       $dpr = $ndsl;

//       $dsl = Sale::where(['product_id'=>$product_id])->count();
//       $dst = $dpr - $dsl;

//       $tsl = Smsdetail::where(['product_id'=>$product_id])->count();
//       $rst = $dsl-$tsl;

//       $dmsl = Sale::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 month")),date("Y-m-d")])
//                     ->count();

//       $dwsl = Sale::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime('last sunday')),date('Y-m-d',strtotime('last sunday'.'+6 days'))])
//                     ->count();

//       $dwsl1 = $dwsl;

//       $ddlysl = Sale::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date("Y-m-d"),date("Y-m-d")])
//                     ->count();

//       $cmsl = Smsdetail::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 month")),date("Y-m-d")])
//                     ->count();


//       $cwsl = Smsdetail::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime('last sunday')),date('Y-m-d',strtotime('last sunday'.'+6 days'))])
//                     ->count();

//       $cwsl1 = $cwsl;
                    
//       $cdlysl = Smsdetail::where(['product_id'=>$product_id])
//                     ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date("Y-m-d"),date("Y-m-d")])
//                     ->count();



//     if ($cwsl1 == 0) {
//       $cdos = $rst/1;
//     }else{
//       $cwsl1 = ceil($cwsl1/7);
//       $cdos = $rst/$cwsl1;
//     }


//     if ($cwsl1 == 0) {
//       $ddos = $dst/1;
//     }else{
//       $cwsl1 = ceil($cwsl1/7);
//       $ddos = $dst/$cwsl1;
//     } 
        
          

//        $level1Data[]=[
//          'pdt'=>$pdt,
//          'cat'=>$cat,
//          'brand'=>$brand,
//          'ndpr'=>$ndpr,
//          'ndsl'=>$ndsl,
//          'ndst'=>$ndst,
//          'dpr'=>$dpr,
//          'dsl'=>$dsl,
//          'dst'=>$dst,
//          'tsl'=>$tsl,
//          'rst'=>$rst,
//          'ddlysl'=>$ddlysl,
//          'dwsl'=>$dwsl,
//          'dmsl'=>$dmsl,
//          'cdlysl'=>$cdlysl,
//          'cwsl'=> $cwsl,
//          'cmsl'=>$cmsl,
//          'ddos'=>round($ddos),
//          'cdos'=>round($cdos),
//        ];

//     }*/
//     //dd($level1Data);
// //==================================

//     //==========================================================================


//     return view('topmanagement.dashboard', [
//       'data' => $data, /*'level1Data'=>$level1Data,*/
//       'dayinmonthchartdata' => $dayinmonthchartdata,
//       'monthinyearchartdata' => $monthinyearchartdata,
//       'monthlytopproductchart' => $monthlytopproductchart,
//       'monthlytopretailerchart' => $monthlytopretailerchart,
//       'todaybrandwisesalechart' => $todaybrandwisesalechart,
//       'monthlybrandwisesalechart' => $monthlybrandwisesalechart,
//       'monthlytopproductsalechart' => $monthlytopproductsalechart,
//       'monthlytopdistributorchart' => $monthlytopdistributorchart
//     ]);
//   }

  public function Test(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    return "this is retailer testing page";
  }

  public function DashboardView()
{
    if (Auth::user()->level != 400) {
        return redirect()->route('logout');
    }

    $_SESSION['requestRetailerCount'] = Cache::remember('requestRetailerCount', 86400, function () {
        return User::where(['active' => 0, 'status' => 0, 'level' => 200])->count();
    });

    $_SESSION['returnCount'] = Cache::remember('returnCount', 86400, function () {
        return Preturn::where('status', '<=', 2)->count();
    });

    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    $data['totalSale'] = Cache::remember('totalSale', 86400, function () {
        return Smsdetail::count();
    });

    $data['monthlySale'] = Cache::remember('monthlySale', 86400, function () {
        return Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
    });

    $data['todaySale'] = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();

    $dayinmonthchartdata = Cache::remember('dayinmonthchartdata', 86400, function () {
        $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $result = [];

        for ($d = 1; $d <= $days; $d++) {
            $time = mktime(12, 0, 0, date('m'), $d, date('Y'));
            $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date('Y-m-d', $time))->count();
            $result[] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'sale' => $count];
        }

        return $result;
    });

    $monthinyearchartdata = Cache::remember('monthinyearchartdata', 86400, function () {
        $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        $result = [];

        foreach ($months as $month) {
            $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-$month"))->count();
            $result[] = ['year' => date('Y'), 'month' => $month, 'sale' => $count];
        }

        return $result;
    });

    $monthlytopproductchart = Cache::remember('monthlytopproductchart', 86400, function () {
        return DB::table('smsdetails as t1')
            ->select(
                DB::raw('COUNT(t1.product_id) as sale'),
                DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
            )
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
            ->groupBy('product_id')
            ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
            ->take(5)
            ->get();
    });

    $monthlytopretailerchart = Cache::remember('monthlytopretailerchart', 86400, function () {
        return DB::table('smsdetails as t1')
            ->select(
                DB::raw('COUNT(t1.user_id) as sale'),
                DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,15), "-", officeid) FROM users WHERE id = t1.user_id) as user')
            )
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
            ->groupBy('user_id')
            ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
            ->take(5)
            ->get();
    });

    $todaybrandwisesalechart = Cache::remember('todaybrandwisesalechart', 86400, function () {
        $brands = Brand::select('id', 'name')->get();
        $result = [];

        foreach ($brands as $brand) {
            $sale = Smsdetail::where('brand_id', $brand->id)
                ->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))
                ->count();

            $result[] = ['name' => $brand->name, 'sale' => $sale];
        }

        return $result;
    });

    $monthlybrandwisesalechart = Cache::remember('monthlybrandwisesalechart', 86400, function () {
        $brands = Brand::select('id', 'name')->get();
        $result = [];

        foreach ($brands as $brand) {
            $sale = Smsdetail::where('brand_id', $brand->id)
                ->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))
                ->count();

            $result[] = ['name' => $brand->name, 'sale' => $sale];
        }

        return $result;
    });

    $monthlytopproductsalechart = Cache::remember('monthlytopproductsalechart', 86400, function () {
        return DB::table('sales as t1')
            ->select(
                DB::raw('COUNT(t1.product_id) as sale'),
                DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
            )
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
            ->groupBy('product_id')
            ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
            ->take(5)
            ->get();
    });

    $monthlytopdistributorchart = Cache::remember('monthlytopdistributorchart', 86400, function () {
        return DB::table('sales as t1')
            ->select(
                DB::raw('COUNT(t1.user_id) as sale'),
                DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,18), "-", officeid) FROM users WHERE id = t1.user_id) as user')
            )
            ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
            ->groupBy('user_id')
            ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
            ->take(5)
            ->get();
    });

    return view('topmanagement.dashboard', [
        'data' => $data,
        'dayinmonthchartdata' => $dayinmonthchartdata,
        'monthinyearchartdata' => $monthinyearchartdata,
        'monthlytopproductchart' => $monthlytopproductchart,
        'monthlytopretailerchart' => $monthlytopretailerchart,
        'todaybrandwisesalechart' => $todaybrandwisesalechart,
        'monthlybrandwisesalechart' => $monthlybrandwisesalechart,
        'monthlytopproductsalechart' => $monthlytopproductsalechart,
        'monthlytopdistributorchart' => $monthlytopdistributorchart
    ]);
}








//================WcheckProduct=======================

  
  public function WcheckProductViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('topmanagement.wcheckProducts_print',['ssdata'=>$ssdata,'wcheckProducts'=>$wcheckProducts,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userwcheckProducts.pdf');

  }


  
  public function WcheckProductView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

    
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

    return view('topmanagement.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('topmanagement.wcheckProduct'));


  }

//================WcheckProduct=======================





// Topmanagement =======================================
  
  public function TopmanagementView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    //$userCount = User::count();
    
    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where('id',Auth::id())->where('level',400)->orderBy('id','desc')->paginate(400);
    //$users = $userResult->toArray();

//dd($users);

    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('topmanagement.topmanagement',['users'=>$userResult,
      'divisions'=>$divisions, 'districts'=>$districts,'upazilas'=>$upazilas]);

  }


  public function TopmanagementUpdate(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
        //$user->lastname = $request->get('lastname');
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
    if (Auth::user()->level != 400) { return redirect()->route('logout');}    
    
    //dd($request->all());

    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        'password' => 'required|min:3|max:400',
        'confirm_password' => 'required|min:3|max:400|same:password',
      ]); 

      $user->password = bcrypt($request['password']);
      $user->save();

    }

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }




// Topmanagement =======================================


  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}


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







//================1DailyDistributorSalesReportView=======================

  
  public function DailyDistributorSalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
    $dailyDistributorSalesReports = [];

    if ($user_id) {
      
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;


    }




    $pdf = PDF::loadView('distributor.dailySalesReports_print',['ssdata'=>$ssdata,'dailyDistributorSalesReports'=>$dailyDistributorSalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailyDistributorSalesReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
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


    /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(400);
    //$sales = $saleResult->toArray();*/

    $ssdata = [];
    $totalamount = [];
    $dailyDistributorSalesReports = [];

    if ($distributor_id == 'all' && $fdate && $todate && !$sno) {
      $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;


        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);

    }elseif($distributor_id != 'all' && $fdate && $todate && !$sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;
        

        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where(['user_id'=>$distributor_id])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


    }elseif($distributor_id == 'all' && $fdate && $todate && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;

        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where(['sno'=>$sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


    }elseif($distributor_id != 'all' && $fdate && $todate && $sno){
        $ssdata['fdate'] = $fdate; $ssdata['todate'] = $todate; $ssdata['distributor_id'] = $distributor_id; $ssdata['sno'] = $sno;


        $dailyDistributorSalesReports = Sale::with('user','product','retailer','sr','salereturn')->select('id','sr_id','user_id','product_id','retailer_id','sno','imei','created_at')->where(['user_id'=>$distributor_id,'sno'=>$sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);



    }else{
      $dailyDistributorSalesReports = [];
    }
//dd($dailyDistributorSalesReports);

//Session::forget(['retailer_id','user_id','sno','fdate','todate']);

//dd(Auth::id());


    return view('topmanagement.dailydistributorSalesReport',['brands'=>$brands,'distributors'=>$distributors,'ssdata'=>$ssdata,'dailyDistributorSalesReports'=>$dailyDistributorSalesReports]);

  }


  public function DailyDistributorSalesReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

    


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


    return redirect(route('topmanagement.dailyDistributorSalesReport'));


  }

//================1DailyDistributorSalesReportView======================





//================2DailySalesReportView=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('topmanagement.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    $userResult = User::select('id','firstname','officeid')->where('level',200)->orderBy('id','desc')->get();
    $users = $userResult->toArray();



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

    return view('topmanagement.dailySalesReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);

  }


  public function DailySalesReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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


    return redirect(route('topmanagement.dailySalesReport'));


  }

//================2DailySalesReportView======================









//================3DailyCampaignReportView=======================

  
  public function DailyCampaignReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('topmanagement.dailyCampaignReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailyCampaignReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    $userResult = User::select('id','firstname','officeid')->where('level',200)->orderBy('id','desc')->get();
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

    return view('topmanagement.dailyCampaignReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailyCampaignReports'=>$dailyCampaignReports]);

  }


  public function DailyCampaignReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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


    return redirect(route('topmanagement.dailyCampaignReport'));


  }

//================3DailyCampaignReportView======================














//================4DailyReplaceReportView=======================

  
  public function DailyReplaceReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
    $dailyReplaceReports = [];

    if ($user_id) {
      
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;


    }




    $pdf = PDF::loadView('topmanagement.dailyReplaceReports_print',['ssdata'=>$ssdata,'dailyReplaceReports'=>$dailyReplaceReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailyReplaceReports.pdf');

  }


  
  public function DailyReplaceReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
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


      $dailyReplaceReports = Replace::with('smsdetail')
          ->select('id','smsdetail_id','imei','sno',DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as rplsdate'))
          ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
          ->get();


      //dd($dailyReplaceReports);
}



    return view('topmanagement.dailyReplaceReport',['ssdata'=>$ssdata,'dailyReplaceReports'=>$dailyReplaceReports,'dataCount'=>$dataCount]);

  }


  public function DailyReplaceReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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


    return redirect(route('topmanagement.dailyReplaceReport'));


  }

//================4DailyReplaceReportView======================









//================5DailyStockReportView=======================

  
  public function DailyStockReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
    $dailyStockReports = [];

if ($user_id) {
  
$ssdata['fdate'] = $fdate;
$ssdata['todate'] = $todate;


}




    $pdf = PDF::loadView('topmanagement.dailyStockReports_print',['ssdata'=>$ssdata,'dailyStockReports'=>$dailyStockReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailyStockReports.pdf');

  }






//================DailyRetailerStockReport=======================
  
  public function DailyRetailerStockReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
    $productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
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

    $productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
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

    return view('topmanagement.dailyRetailerStockReport',['ssdata'=>$ssdata,'dailyRetailerStockReports'=>$dailyRetailerStockReports,'retailers' => $retailers]);

  }


  public function DailyRetailerStockReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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

  return redirect(route('topmanagement.dailyRetailerStockReport'));


  }

//================DailyRetailerStockReport=======================






  
  public function DailyStockReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
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
    $productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
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

    $productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
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

    return view('topmanagement.dailyStockReport',['ssdata'=>$ssdata,'dailyStockReports'=>$dailyStockReports,'distributors' => $distributors]);

  }


  public function DailyStockReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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

  return redirect(route('topmanagement.dailyStockReport'));


  }

//================5DailyStockReportView=======================






//================6DailyPurchaseSaleReportView=======================
  public function DailyPurchaseSaleReportView(){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    $distributorResult = User::where('level',100)->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();

$productResult = Product::orderBy('id','desc')->get();
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
          $purchases = Purchase::with('product','user')->select('id','order_number','user_id','product_id','quantity','sno','imei','created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(400);
        } elseif($type == "Sale") {
          $sales = Sale::with('product','retailer','user')->select('id','user_id','product_id','retailer_id','sno','imei','created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(400);
        }
      //with distributor_id all===========
    } else {
      //with distributor_id===========
        if ($type == "Purchase") {
          $purchases = Purchase::with('product','user')->select('id','order_number','user_id','product_id','quantity','sno','imei','created_at')->where('user_id',$distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(400);
        } elseif($type == "Sale") {
          $sales = Sale::with('product','retailer','user')->select('id','user_id','product_id','retailer_id','sno','imei','created_at')->where('user_id',$distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(400);
        }
      //with distributor_id===========
    }
     





}


//Session::forget(['user_id','fdate','todate']);

    return view('topmanagement.dailyPurchaseSaleReport',['ssdata'=>$ssdata,'dailyPurchaseSaleReports'=>$dailyPurchaseSaleReports,'distributors' => $distributors,'sales'=>$sales,'purchases'=>$purchases,'retailers'=>$retailers,'products'=>$products]);

  }


  public function DailyPurchaseSaleReportViewStore(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}

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

  return redirect(route('topmanagement.dailyPurchaseSaleReport'));


  }



  public function PurchaseUpdate(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    $id = $request->get('id');
    $Purchase = Purchase::find($id);
    
    if ($Purchase === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request,['sno'=>'required']);
      //$Purchase->product_id = $request->get('product_id');
      $Purchase->sno = $request->get('sno');


      $Purchase->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
    }

 
  }



  public function PurchaseDestroy($id){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
    $Purchase = Purchase::find($id);
    
    $productCount = 0;
    //$productCount = Product::where('promo_id', $id)->count();
    //$product = Product::where('promo_id', $id)->get();

    if ($Purchase === null) {
      return redirect()->back()->withErrors('There are no data with this id');

    }else{
      if ($productCount > 0) {
        return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
      }else{
        $Purchase->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }
    

 
  }

  public function SaleUpdate(Request $request){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    $id = $request->get('id');
    $Sale = Sale::find($id);
    
    if ($Sale === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request,['retailer_id'=>'required']);
      $Sale->retailer_id = $request->get('retailer_id');
      $Sale->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
    }

 
  }



  public function SaleDestroy($id){
    if (Auth::user()->level != 400) { return redirect()->route('logout');}
    
    $Sale = Sale::find($id);
    
    $productCount = 0;
    //$productCount = Product::where('promo_id', $id)->count();
    //$product = Product::where('promo_id', $id)->get();

    if ($Sale === null) {
      return redirect()->back()->withErrors('There are no data with this id');

    }else{
      if ($productCount > 0) {
        return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
      }else{
        $Sale->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }
    

 
  }



//================6DailyPurchaseSaleReportView=======================

public function wodReport()
  {
  
    //$userCount = User::count();


    $distributorResult = User::where('level', 100)->orderBy('id', 'desc')->get();
    $distributors = $distributorResult->toArray();

    $brandResult = Brand::orderBy('id', 'desc')->get();
    $brands = $brandResult->toArray();

    $productResult = Product::orderBy('id', 'desc')->get();
    $products = $productResult->toArray();

    $retailerResult = Retailer::orderBy('id', 'desc')->get();
    $retailers = $retailerResult->toArray();

    $srResult = Sr::orderBy('id', 'desc')->get();
    $srs = $srResult->toArray();


    $ssdata = [];
    $totalamount = [];
    $dailyPurchaseSaleReport1s = [];

    $ssdata['count'] = 0;

    $purchases = [];
    $wod = [];


    //dd(Session::all());

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    //$type = Session::get('type');
    $distributor_id = Session::get('distributor_id');
    $brand_id = Session::get('brand_id');

    $sales = [];
    if ($fdate && $todate && $brand_id && $distributor_id) {
      //--------------------------------------------------
      $ssdata['count'] = 1;
      //$ssdata['type'] = $type;
      $ssdata['fdate'] = $fdate;
      $ssdata['todate'] = $todate;
      //--------------------------------------------------


      //with distributor_id all===========



      // $sales = Sale::with('product','retailer','user','sr','district','upazila','brand')->select('id','user_id','product_id',count('retailer_id') as rcount,'dis_id','up_id','sr_id','brand_id','sno','imei',sum('quantity') as qty,'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->get();

      if ($brand_id == "All" && $distributor_id == "All") {
        $query = DB::table('sales as t1')
          ->select(
            (DB::raw("SUM(t1.quantity) as qty")),
            (DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
            't3.name as product',
            't3.model as model',
            't4.name as brand',
            't5.name as category'

          )
          //->join('brands as t2', 't1.brand_id', '=', 't2.id')

          ->join('products as t3', 't1.product_id', '=', 't3.id')
          ->join('brands as t4', 't1.brand_id', '=', 't4.id')
          ->join('cats as t5', 't3.cat_id', '=', 't5.id')
          //->where('t1.brand_id', $brand_id )

          //->count('t1.retail_id as retailqty')
          ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])

          ->groupBy('t1.product_id')
          ->get();
        //->paginate(5)
        $wod = json_decode(json_encode($query), True);




        //    $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
        //         ->where(['user_id'=>$distributor_id,'product_id'=>$product_id])
        //         ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        //         ->groupBy('product_id')
        //         ->first();
        //     $sales = $query->toArray();

        //     $data2[$key] = $sales['quantity'];
        // //with distributor_id all===========

      } elseif ($brand_id == "All") {
        $query = DB::table('sales as t1')
          ->select(
            (DB::raw("SUM(t1.quantity) as qty")),
            (DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
            't3.name as product',
            't3.model as model',
            't4.name as brand',
            't5.name as category'

          )
          //->join('brands as t2', 't1.brand_id', '=', 't2.id')

          ->join('products as t3', 't1.product_id', '=', 't3.id')
          ->join('brands as t4', 't1.brand_id', '=', 't4.id')
          ->join('cats as t5', 't3.cat_id', '=', 't5.id')
          //->where('t1.brand_id', $brand_id )
          ->where('t1.user_id', $distributor_id)

          //->count('t1.retail_id as retailqty')
          ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])

          ->groupBy('t1.product_id')
          ->get();
        //->paginate(5)
        $wod = json_decode(json_encode($query), True);



      } elseif ($distributor_id == "All") {
        $query = DB::table('sales as t1')
          ->select(
            (DB::raw("SUM(t1.quantity) as qty")),
            (DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
            't3.name as product',
            't3.model as model',
            't4.name as brand',
            't5.name as category'

          )
          //->join('brands as t2', 't1.brand_id', '=', 't2.id')

          ->join('products as t3', 't1.product_id', '=', 't3.id')
          ->join('brands as t4', 't1.brand_id', '=', 't4.id')
          ->join('cats as t5', 't3.cat_id', '=', 't5.id')
          ->where('t1.brand_id', $brand_id)
          //->where('t1.user_id', $distributor_id )

          //->count('t1.retail_id as retailqty')
          ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])

          ->groupBy('t1.product_id')
          ->get();
        //->paginate(5)
        $wod = json_decode(json_encode($query), True);



      } else {
        $query = DB::table('sales as t1')
          ->select(
            (DB::raw("SUM(t1.quantity) as qty")),
            (DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
            't3.name as product',
            't3.model as model',
            't4.name as brand',
            't5.name as category'

          )
          //->join('brands as t2', 't1.brand_id', '=', 't2.id')

          ->join('products as t3', 't1.product_id', '=', 't3.id')
          ->join('brands as t4', 't1.brand_id', '=', 't4.id')
          ->join('cats as t5', 't3.cat_id', '=', 't5.id')
          ->where('t1.brand_id', $brand_id)
          ->where('t1.user_id', $distributor_id)

          //->count('t1.retail_id as retailqty')
          ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])

          ->groupBy('t1.product_id')
          ->get();
        //->paginate(5)
        $wod = json_decode(json_encode($query), True);



      }


    }




    //Session::forget(['user_id','fdate','todate']);

    return view('topmanagement.wodReport', ['ssdata' => $ssdata, 'dailyPurchaseSaleReport1s' => $dailyPurchaseSaleReport1s, 'distributors' => $distributors, 'brands' => $brands, 'sales' => $wod]);

  }


  public function wodReportStore(Request $request)
  {
    

    //dd($request->all());

    Session::forget(['fdate', 'todate', 'all_report']);


    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'brand_id' => 'required',
      'distributor_id' => 'required',
      //'type' => 'required',
    ]);


    $brand_id = $request->get('brand_id');
    $distributor_id = $request->get('distributor_id');
    // $type = $request->get('type');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');

    Session::put(['fdate' => $fdate, 'todate' => $todate, 'brand_id' => $brand_id, 'distributor_id' => $distributor_id]);

    return redirect(route('topmanagement.wodReport'));


  }

public function primarySecondaryDLReport()
  {
    
    //$userCount = User::count();


    $distributorResult = User::where('level', 100)->orderBy('id', 'desc')->get();
    $distributors = $distributorResult->toArray();


    $productResult = Product::orderBy('id', 'desc')->get();
    $products = $productResult->toArray();

    $retailerResult = Retailer::orderBy('id', 'desc')->get();
    $retailers = $retailerResult->toArray();

    $srResult = Sr::orderBy('id', 'desc')->get();
    $srs = $srResult->toArray();


    $ssdata = [];
    $totalamount = [];
    $dailyPurchaseSaleReport1s = [];
    $ssdata['count'] = 0;

    $purchases = [];
    $sales = [];


    //dd(Session::all());

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    $type = Session::get('type');
    $distributor_id = Session::get('distributor_id');

    $sales = [];
    if ($fdate && $todate && $type && $distributor_id) {
      //--------------------------------------------------
      $ssdata['count'] = 1;
      $ssdata['type'] = $type;
      $ssdata['fdate'] = $fdate;
      $ssdata['todate'] = $todate;
      //--------------------------------------------------

      if ($distributor_id == "All") {
        //with distributor_id all===========
        if ($type == "Purchase") {
          $purchases = Purchase::with('product', 'user', 'district', 'upazila', 'brand')->select('id', 'order_number','user_id', 'dis_id', 'up_id', 'brand_id', 'product_id', 'quantity', 'sno', 'status', 'imei', 'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->get();
        } elseif ($type == "Sale") {
          $sales = Sale::with('product', 'retailer', 'user', 'sr', 'district', 'upazila', 'brand')->select('id', 'memo', 'user_id', 'product_id', 'retailer_id', 'dis_id', 'up_id', 'sr_id', 'brand_id', 'sno', 'imei', 'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->get();
        }
        //with distributor_id all===========
      } else {
        //with distributor_id===========
        if ($type == "Purchase") {
          $purchases = Purchase::with('product', 'user', 'district', 'upazila', 'brand')->select('id', 'order_number', 'user_id', 'dis_id', 'up_id', 'brand_id', 'product_id', 'quantity', 'sno', 'status', 'imei', 'created_at')->where('user_id', $distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->get();
        } elseif ($type == "Sale") {
          $sales = Sale::with('product', 'retailer', 'user', 'sr', 'district', 'upazila', 'brand')->select('id', 'memo', 'user_id', 'retailer_id', 'dis_id', 'up_id', 'sr_id', 'brand_id', 'product_id', 'sno', 'imei', 'created_at')->where('user_id', $distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->get();
          //dd($sales);

        }
        //with distributor_id===========
      }


    }

    //Session::forget(['user_id','fdate','todate']);

    return view('topmanagement.primarySecondaryDLReport', ['ssdata' => $ssdata, 'dailyPurchaseSaleReport1s' => $dailyPurchaseSaleReport1s, 'distributors' => $distributors, 'sales' => $sales, 'purchases' => $purchases]);

  }







  public function primarySecondaryDLReportStore(Request $request)
  {

    //dd($request->all());

    Session::forget(['fdate', 'todate', 'all_report']);


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

    Session::put(['fdate' => $fdate, 'todate' => $todate, 'type' => $type, 'distributor_id' => $distributor_id]);

    return redirect(route('topmanagement.primarySecondaryDLReport'));


  }


  public function PrimaryViewExcel()
  {


    // return Excel::download(new StocksExport, 'stocks.xlsx');


    $query = DB::table('purchases as t1')
      ->select(
        't1.order_number as order_number',
        't5.firstname as dis_name',
        't5.officeid as dis_id',
        't3.name as product',
        't3.model as model',
        't4.name as brand',
        't3.color as color',
        't1.sno as imei 1',
        't1.imei as imei 2',
        DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d') as Date")
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')
      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('users as t5', 't1.user_id', '=', 't5.id')
      //->join('districts as t6', 't1.dis_id', '=', 't6.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->orderBy('t1.id', 'desc')
      ->latest('t1.id')
      ->take(200000)
      ->get();
    //->paginate(5)
    $stocks = json_decode(json_encode($query), True);

    return (new FastExcel($stocks))->download('primary.xlsx');


  }

  // Full secondary sales download in Excel- ND purchase Page
  public function SeconderyViewExcel()
  {

    // return Excel::download(new StocksExport, 'stocks.xlsx');


    $query = DB::table('sales as t1')
      ->select(
        't5.firstname as dis_name',
        't5.officeid as dis_id',
        't8.firstname as reatiler_name',
        't8.officeid as retailer_id',
        't1.memo as memo',
        't3.name as product',
        't3.model as model',
        't4.name as brand',
        't3.color as color',
        't1.sno as imei 1',
        't1.imei as imei 2',
        DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d') as Date")
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')
      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('users as t5', 't1.user_id', '=', 't5.id')
      ->join('users as t8', 't1.ruser_id', '=', 't8.id')
      //->join('districts as t6', 't1.dis_id', '=', 't6.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->orderBy('t1.id', 'desc')
      ->take(10000000)
      ->get();
    //->paginate(5)
    $stocks = json_decode(json_encode($query), True);

    return (new FastExcel($stocks))->download('secondary.xlsx');


  }


  public function dosReport()
  {
    
    //$userCount = User::count();


    $distributorResult = User::where('level', 100)->orderBy('id', 'desc')->get();
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
      $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
      $products = $productResult->toArray();


      foreach ($products as $key => $product1) {
        $product_id = $product1['id'];
        $product = $product1['name'];
        $model = $product1['model'];



        if ($distributor_id == "All") {

          // with distributor_id========
          $pcount = Purchase::where('product_id', $product_id)->count();

          if ($pcount > 0) {
            $PurchaseResult = Purchase::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('product_id', $product_id)->groupBy('product_id')->first();
            $Purchases = $PurchaseResult->toArray();

            //$distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
            $distributor = "All Distributoirs";
            $sin = $Purchases['sin'];
          } else {
            $distributor = "All Distributoirs";
            $sin = 0;
          }

          $scount = Sale::where('product_id', $product_id)->count();

          if ($scount > 0) {
            $SaleResult = Sale::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('product_id', $product_id)->groupBy('product_id')->first();
            $Sales = $SaleResult->toArray();

            //$distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
            $distributor = "All Distributoirs";
            $sout = $Sales['sout'];
          } else {
            $distributor = "All Distributoirs";
            $sout = 0;
          }


          $currentDate = date('Y-m-d');
          $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

          $scount30days = Sale::where('product_id', $product_id)
            ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
            ->count();


          $dailyStockReports[] = [
            'distributor' => $distributor,
            'product_id' => $product_id,
            'product' => $product,
            'model' => $model,
            'stockin' => $sin,
            'stockout' => $sout,
            'stock' => $sin - $sout,
            'lastmonth_sale' => $scount30days
          ];


          // with distributor_id========
        } else {

          // with distributor_id========
          $pcount = Purchase::where('user_id', $distributor_id)->where('product_id', $product_id)->count();

          if ($pcount > 0) {
            $PurchaseResult = Purchase::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('user_id', $distributor_id)->where('product_id', $product_id)->groupBy('product_id')->first();
            $Purchases = $PurchaseResult->toArray();

            $distributor = $Purchases['user']['firstname'] . " - " . $Purchases['user']['officeid'];
            $sin = $Purchases['sin'];
          } else {
            //$distributor = " - ";
            $sin = 0;
            $userData = User::where('id', $distributor_id)->first();
            $distributor = $userData->firstname . " " . $userData->officeid;
          }

          $scount = Sale::where('user_id', $distributor_id)->where('product_id', $product_id)->count();

          if ($scount > 0) {
            $SaleResult = Sale::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('user_id', $distributor_id)->where('product_id', $product_id)->groupBy('product_id')->first();
            $Sales = $SaleResult->toArray();

            $distributor = $Sales['user']['firstname'] . " - " . $Sales['user']['officeid'];
            $sout = $Sales['sout'];
          } else {
            //$distributor = " - ";
            $sout = 0;
            $userData = User::where('id', $distributor_id)->first();
            $distributor = $userData->firstname . " " . $userData->officeid;
          }

          // with distributor_id========


          $currentDate = date('Y-m-d');
          $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

          $scount30days = Sale::where('user_id', $distributor_id)
            ->where('product_id', $product_id)
            ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
            ->count();




          $dailyStockReports[] = [
            'distributor' => $distributor,
            'product_id' => $product_id,
            'product' => $product,
            'model' => $model,
            'stockin' => $sin,
            'stockout' => $sout,
            'stock' => $sin - $sout,
            'lastmonth_sale' => $scount30days
          ];

        }




      }
      //--------------------------------------------------
    }



    //Session::forget(['user_id','fdate','todate']);

    return view('topmanagement.dosReport', ['ssdata' => $ssdata, 'dailyStockReports' => $dailyStockReports, 'distributors' => $distributors]);

  }


  public function dosReportStore(Request $request)
  {
    //dd($request->all());

    Session::forget(['fdate', 'todate', 'all_report']);


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

      Session::put(['fdate' => $fdate, 'todate' => $todate, 'distributor_id' => $distributor_id]);
    } else {
      $this->validate($request, [
        'all_report' => 'required',
        'distributor_id' => 'required',
      ]);
      $distributor_id = $request->get('distributor_id');
      $all_report = $request->get('all_report');

      Session::put(['all_report' => $all_report, 'distributor_id' => $distributor_id]);
    }

    return redirect(route('topmanagement.dosReport'));


  }

  //================DistributorImeiStockReportView=======================

  public function distributorImeiStockReport()
  {
    

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
      ->orderBy('t1.id', 'desc')
      //->take(10)
      ->get();
    //->paginate(5)
    $queryresults = json_decode(json_encode($query), True);

    //dd($queryresults);


    return view('topmanagement.distributorImeiStockReport', ['queryresults' => $queryresults]);

  }

  //================DistributorImeiStockReportView======================

  public function retailerImeiStockReport()
  {
    

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
      ->orderBy('t1.id', 'desc')
      //->take(10)
      ->get();
    //->paginate(5)
    $queryresults = json_decode(json_encode($query), True);

    //dd($queryresults);


    return view('topmanagement.retailerImeiStockReport', ['queryresults' => $queryresults]);

  }

  //================RetailerImeiStockReportView======================








}
