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
use App\Models\Order;



class TsoController extends Controller
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
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
  }

  public function DashboardView(){
     if (Auth::user()->level != 10) { return redirect()->route('logout');}



    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    $data['totalSale'] = Smsdetail::select('id')->where('user_id',Auth::id())->count();
    $data['orader'] = Order::where('user_id',Auth::id())->get();
    $data['monthlySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"),date("Y-m") )->count();
    $data['todaySale'] = Smsdetail::select('id')->where('user_id',Auth::id())->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date("Y-m-d") )->count();
     
    return view('tso.dashboard',['data' => $data]);
    
  }

  public function Test(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    return "this is retailer testing page";
  }






  public function storetoOraderpPosting(Request $request)
  {

    dd($request->all());
      // request()->validate(Orderspostingdetail::$rules);

      // $orderspostingdetail = Orderspostingdetail::create($request->all());

      // return redirect()->route('orderspostingdetails.index')
      //     ->with('success', 'Orderspostingdetail created successfully.');
  }



// Retailer =======================================
  
  public function RetailerView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    //$userCount = User::count();
    

    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where(['tso_id'=>Auth::id(),'level'=>200])->orderBy('id','desc')->paginate(300);
    //$users = $userResult->toArray();

//dd($userResult);
//

    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('tso.retailer',['retailers'=>$userResult, 'divisions'=>$divisions, 'districts'=>$districts, 'upazilas'=>$upazilas  ]);

  }

  public function RetailerViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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
        //'level' => 'required'
      ]); 

 $officeid="SX".mt_rand(5, 100).date("mH");

        $image = $request->file('image');
        
        if (!is_null($image)) {
          $this->validate($request, [
              'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]); 


        $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
        Storage::put($image_name, file_get_contents($image));
        }else{
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
        $request['active'] = 0;
        $request['status'] = 0;
        
        $data=User::create($request->all());  
        //dd($data->id);

      return redirect()->back()->with('success', 'Data has been inserted successfully');

//========================================================================================


 
  }

  public function RetailerUpdate(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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
    }else{
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

        $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
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
        }elseif($district_id){
          $user->district_id = $district_id;
        }elseif($upazila_id){
          $user->upazila_id = $upazila_id;
        }

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
        if ($division_id) {
          $user->division_id = $division_id;
        }elseif($district_id){
          $user->district_id = $district_id;
        }elseif($upazila_id){
          $user->upazila_id = $upazila_id;
        }
        $user->save();

      //=================================================================

        }

    return redirect()->back()->with('success', 'Data has been updated successfully');   
 
  }

 
  }



  public function RetailerDestroy($id){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    $user = User::find($id);

    //===========
    $active = $user->active;
    if ($active == true) {
      return redirect()->back()->withErrors('This user can not be deleted due to user status');
    }
    //===========


    $smsdetailCount = Smsdetail::where(['user_id'=>$id])->count();
    $retailerCount = Retailer::where(['user_id'=>$id])->count();
    //$srCount = Sr::where(['user_id'=>$id])->count();

    $middistrictCount = Middistrict::where(['user_id'=>$id])->count();


    $retailerCount2 = Retailer::where(['retailer_id'=>$id])->count();
    //$srCount2 = Sr::where(['sr_id'=>$id])->count();


    



    if($smsdetailCount > 0 || $retailerCount > 0 ||  $retailerCount2 > 0 || $middistrictCount > 0 ){
      return redirect()->back()->withErrors('This user can not be deleted due to related to other data');
    }

    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{

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
      
      $retailerCount1 = Retailer::where(['retailer_id'=>$id])->count();
      if ($retailerCount1 > 0) {
        DB::table('retailers')->where('retailer_id', $id)->delete();
      }
//====================================
      

      $user->delete();
      return redirect()->back()->with('success', 'Data has been deleted successfully');
    }
    

 
  }

// Retailer =======================================












//================DailySalesReport=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('tso.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',10)->where('id',Auth::id())->orderBy('id','desc')->get();
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

    return view('tso.dailySalesReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);

  }


  public function DailySalesReportViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    


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


    return redirect(route('tso.dailySalesReport'));


  }

//================DailySalesReport======================

//================DailyCampaignReport=======================

  
  public function DailyCampaignReportViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('tso.dailyCampaignReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailyCampaignReportView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('id',Auth::id())->where('level',10)->orderBy('id','desc')->get();
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

    return view('tso.dailyCampaignReport',['brands'=>$brands,'users'=>$users,'ssdata'=>$ssdata,'dailyCampaignReports'=>$dailyCampaignReports]);

  }


  public function DailyCampaignReportViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

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


    return redirect(route('tso.dailyCampaignReport'));


  }

//================dailyCampaignReport======================





//================DailyRetailerStockReportForDistrict=======================

  
  
  public function DailyRetailerStockReportForUpazilaView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

 


    $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();



    $upazila_id = Session::get('upazila_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['upazila_id'=>$upazila_id,'level'=>200])->get();
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


if ($upazila_id) {
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
  $userResult = retailer::select('id','name','officeid')->where(['user_id'=>$upazila_id])->get();
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
  $retailer = $userData['firstname'] . " - " . $userData['officeid'];
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
  $retailer = $userData['firstname'] . " " . $userData['officeid'];
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

    return view('tso.dailyRetailerStockReportForUpazila',[
      'upazilas'=>$upazilas,'ssdata'=>$ssdata,'dailyRetailerStockReports'=>$dailyRetailerStockReports]);

  }


  public function DailyRetailerStockReportForUpazilaViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['upazila_id','user_id','fdate','todate']);

    $this->validate($request, [
      'upazila_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $upazila_id = $request->get('upazila_id');
    $user_id = $request->get('user_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['upazila_id'=>$upazila_id,'user_id'=>$user_id,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('tso.dailyRetailerStockReportForUpazila'));


  }

//================DailyRetailerStockReportForDistrict======================










//================DailySalesReportWithUpazila=======================

  
  public function DailySalesReportWithUpazilaView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$upazilaResult = Upazila::select('id','name')->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();*/


    $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

    //dd($upazilas);



    /*$userResult = User::select('id','firstname','officeid')->where('level',10)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    /*$userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/



//dd($users);

    $upazila_id = Session::get('upazila_id');
    $brand_id = Session::get('brand_id');
    //$retailer_id = Session::get('retailer_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['upazila_id'=>$upazila_id,'level'=>200])->get();
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
    $dailySalesReportWithUpazilas = [];




if ($user_id == 'all') {
  $userResult = retailer::select('retailer_id','name','officeid')->where(['user_id'=>$upazila_id])->get();
  $usersdatas = $userResult->toArray();

  foreach ($usersdatas as $key => $value) {
    $user_id = $value['retailer_id'];

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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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
//dd($dailySalesReportWithUpazilas);
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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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
          $dailySalesReportWithUpazilas[] = [
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


//dd($dailySalesReportWithUpazilas);

//Session::forget(['upazila_id','brand_id','user_id','sno','fdate','todate']);

    return view('tso.dailySalesReportWithUpazila',['brands'=>$brands,
      'upazilas'=>$upazilas,'ssdata'=>$ssdata,'dailySalesReportWithUpazilas'=>$dailySalesReportWithUpazilas]);

  }


  public function DailySalesReportWithUpazilaViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['upazila_id','brand_id','user_id','sno','fdate','todate']);

    $this->validate($request, [
      'upazila_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $upazila_id = $request->get('upazila_id');
    $brand_id = $request->get('brand_id');
    $user_id = $request->get('user_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['upazila_id'=>$upazila_id,'brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    //dd(Session::all());


    return redirect(route('tso.dailySalesReportWithUpazila'));


  }

//================DailySalesReportWithUpazila======================





//================DailyCampaignReportWithUpazila=======================

  
  public function DailyCampaignReportWithUpazilaView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $brandResult = Brand::select('id','name')->orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

    /*$upazilaResult = Upazila::select('id','name')->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();*/


    $upazilaResult = Tsoupazila::select('id','name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

    /*$userResult = User::select('id','firstname','officeid')->where('level',10)->where('id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/


    /*$userResult = Retailer::select('retailer_id as id','name as firstname','officeid')->where('user_id',Auth::id())->orderBy('id','desc')->get();
    $users = $userResult->toArray();*/



//dd($users);

    $upazila_id = Session::get('upazila_id');
    $brand_id = Session::get('brand_id');
    //$retailer_id = Session::get('retailer_id');
    $user_id = Session::get('user_id');
    $sno = Session::get('sno');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');


/*$datar = [];


if ($user_id == 'all') {
  $userResult = User::select('id')->where(['upazila_id'=>$upazila_id,'level'=>200])->get();
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
    $dailyCampaignReportWithUpazilas = [];




if ($user_id == 'all') {
  $userResult = User::select('id')->where(['upazila_id'=>$upazila_id,'level'=>200])->get();
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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
//dd($dailyCampaignReportWithUpazilas);
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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
          $dailyCampaignReportWithUpazilas[] = [
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



//dd($dailyCampaignReportWithUpazilas);

//Session::forget(['upazila_id','brand_id','user_id','sno','fdate','todate']);

    return view('tso.dailyCampaignReportWithUpazila',[
      'brands'=>$brands,'upazilas'=>$upazilas,'ssdata'=>$ssdata,
      'dailyCampaignReportWithUpazilas'=>$dailyCampaignReportWithUpazilas]);

  }


  public function DailyCampaignReportWithUpazilaViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());


   

    Session::forget(['upazila_id','brand_id','user_id','sno','fdate','todate']);

    $this->validate($request, [
      'upazila_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $upazila_id = $request->get('upazila_id');
    $brand_id = $request->get('brand_id');
    $user_id = $request->get('user_id');
    $sno = $request->get('sno');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['upazila_id'=>$upazila_id,'brand_id'=>$brand_id,'user_id'=>$user_id,'sno'=>$sno,'fdate'=>$fdate,'todate'=>$todate]);


    return redirect(route('tso.dailyCampaignReportWithUpazila'));


  }

//================dailyCampaignReportWithUpazila======================




//================WcheckProduct=======================

  
  public function WcheckProductViewPrint($user_id,$fdate,$todate){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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




    $pdf = PDF::loadView('tso.wcheckProducts_print',['ssdata'=>$ssdata,'wcheckProducts'=>$wcheckProducts,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userwcheckProducts.pdf');

  }


  
  public function WcheckProductView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    
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

    return view('tso.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('tso.wcheckProduct'));


  }

//================WcheckProduct=======================





// Tso =======================================
  
  public function TsoView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    //$userCount = User::count();
    
    //$userResult = User::with('territory')->get();
    $userResult = User::with('division','district','upazila')->where('id',Auth::id())->where('level',10)->orderBy('id','desc')->paginate(10);
    //$users = $userResult->toArray();

//dd($users);

    $divisionResult = Division::get();
    $divisions = $divisionResult->toArray();

    $districtResult = District::get();
    $districts = $districtResult->toArray();

    $upazilaResult = Upazila::get();
    $upazilas = $upazilaResult->toArray();

    return view('tso.tso',['users'=>$userResult,
    'divisions'=>$divisions, 'districts'=>$districts,'upazilas'=>$upazilas ]);

  }


  public function TsoUpdate(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
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
    if (Auth::user()->level != 10) { return redirect()->route('logout');}    
    
    //dd($request->all());

    $id = $request->get('id');
    $user = User::find(Auth::id());
    
    if ($user === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request, [
        'password' => 'required|min:3|max:10',
        'confirm_password' => 'required|min:3|max:10|same:password',
      ]); 

      $user->password = bcrypt($request['password']);
      $user->save();

    }

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }




// Tso =======================================


  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}


    $this->validate($request, [
      'cn' => 'required',
      //'imei' => 'required',
      'mobile' => 'required',
       'service_type' => 'required',
      //'imei' => 'required',
      'problem' => 'required',
      ]);
      
  
      $purchases = Purchase::where('sno', $request->get('sno'))
      ->orWhere('imei', $request->get('imei'))
      ->first();
  
  if ($purchases == null) {
  return redirect()->back()->withErrors('This IMEI is not available. Please Contact with Admin.');
  }
  
      
  
    $id = $request->get('id');
    $smsdetail = Smsdetail::find($id);
    
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
  $request1['problem'] = $request->problem;
  $request1['brand_id'] = $smsdetail->brand_id;
  $request1['product_id'] = $smsdetail->product_id;
  $image = $request->file('image');
        
        if (!is_null($image)) {
          $this->validate($request, [
              'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]); 
  
  
        $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
        Storage::put($image_name, file_get_contents($image));
        }else{
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
     
    return redirect()->back()->with('success', 'Data has been inserted successfully');
  
  

  }



  public function DailyPurchaseReportView(){
     if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

    $upazila_id = Session::get('upazila_id');
    $user_id = Session::get('user_id');

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
   $all_report = Session::get('all_report');
   

    $ssdata = [];
    $totalamount = [];
    $dailyPurchaseReports = [];
    $purchases = [];
    $ssdata['count'] = 0;


if( $upazila_id=="All"){

//$purchases = [];

foreach ($upazilas as $key => $upazila) {
    $upazila_id = $upazila['upazila_id']; 

    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;

  
     

    $purchasesval = Purchase::with('product', 'user', 'brand')
        ->select('id', 'user_id', 'product_id', 'quantity', 'brand_id', 'sno', 'imei', 'created_at')
        ->where('user_id', $upazila_id)
        ->where('status', 1)
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get(); // Use get() to retrieve all data without pagination

    if ($purchasesval) {
        $purchasesData = $purchasesval->toArray();

        foreach ($purchasesData as $pur) {
            $purchases[] = [
                'firstname' => $pur['user']['firstname'],
                'officeid' => $pur['user']['officeid'],
                'sno' => $pur['sno'],
                'brand' => $pur['brand']['name'],
                'imei' => $pur['imei'],
                'name' => $pur['product']['name'],
                'model' => $pur['product']['model'],
                'created_at' => $pur['created_at'],
            ];
        }
    } 



}

// Dump the contents of $purchases to check if it contains valid data


}

else{

//--------------------------------------------------
//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------


    $purchasesval = Purchase::with('product', 'user', 'brand')
        ->select('id', 'user_id', 'product_id', 'quantity','brand_id', 'sno', 'imei', 'created_at')
        ->where('user_id', $upazila_id)
        ->where('status', 1)
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        
        ->get(); // Use get() to retrieve all data without pagination
  if ($purchasesval) {
        $purchasesData = $purchasesval->toArray();

        foreach ($purchasesData as $pur) {
            if (isset($pur['user'], $pur['product'])) {
                $purchases[] = [
                    'firstname' => $pur['user']['firstname'],
                    'officeid' => $pur['user']['officeid'],
                    'sno' => $pur['sno'],
                    'imei' => $pur['imei'],
                    'brand' => $pur['brand']['name'],
                    'name' => $pur['product']['name'],
                    'model' => $pur['product']['model'],
                    'created_at' => $pur['created_at'],
                ];
            }
        }
    }
   

}
//--------------------------------------------------
//dd(Session::all());
//dd($purchases);
//Session::forget(['user_id','fdate','todate']);
// dd($purchases);
// exit();

  
    return view('tso.dailyPurchaseReports',[ 'upazilas'=>$upazilas,'ssdata'=>$ssdata,'dailyPurchaseReports'=>$dailyPurchaseReports,'purchases' => $purchases]);



  }

public function DailyPurchaseReportViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['upazila_id','fdate','todate','all_report']);


if ($request->get('all_report') == null) {
    
    $this->validate($request, [
      'upazila_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required',
    ]);

    $upazila_id = $request->get('upazila_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');

    Session::put(['upazila_id'=>$upazila_id,'fdate'=>$fdate,'todate'=>$todate]);
}
else{


    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'all_report' => 'required'
    ]);
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');
    Session::put(['all_report'=>$all_report,'fdate'=>$fdate,'todate'=>$todate]);
}

  return redirect(route('tso.dailyPurchaseReports'));


  }

  public function DailySalesReportViews(){
     if (Auth::user()->level != 10) { return redirect()->route('logout');}
    
    //Session::forget(['brand_id','user_id','sno','fdate','todate']);

    $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

    $upazila_id = Session::get('upazila_id');
    $user_id = Session::get('user_id');

    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
   $all_report = Session::get('all_report');
   

    $ssdata = [];
    $totalamount = [];
    $dailySalesReports = [];
    $sales = [];
    $ssdata['count'] = 0;


if( $upazila_id=="All"){

//$purchases = [];

foreach ($upazilas as $key => $upazila) {
    $upazila_id = $upazila['upazila_id']; 

    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;


  
     

    $saleval = Sale::with('product','retailer','user','sr','district','upazila','brand')
        ->select('id', 'user_id', 'product_id','retailer_id', 'dis_id','up_id','sr_id','brand_id','sno','imei','created_at')
        ->where('user_id', $upazila_id)
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get(); // Use get() to retrieve all data without pagination

    if ($saleval) {
        $saleData = $saleval->toArray();

        foreach ($saleData as $pur) {
            $sales[] = [
                'firstname' => $pur['user']['firstname'],
                'officeid' => $pur['user']['officeid'],
                'rname' => $pur['retailer']['name'],
                 'rid' => $pur['retailer']['officeid'],
                'sno' => $pur['sno'],
                'imei' => $pur['imei'],
                'brand' => $pur['brand']['name'],
                'name' => $pur['product']['name'],
                'model' => $pur['product']['model'],
                'created_at' => $pur['created_at'],
            ];
        }
    } 



}

// Dump the contents of $purchases to check if it contains valid data


}

else{

//--------------------------------------------------
//--------------------------------------------------
    $ssdata['count'] = 1;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------


    $saleval = Sale::with('product','retailer','user','sr','district','upazila','brand')
        ->select('id', 'user_id', 'product_id','retailer_id', 'dis_id','up_id','sr_id','brand_id','sno','imei','created_at')
        ->where('user_id', $upazila_id)
        ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
        ->get(); // Use get() to retrieve all data without pagination

    if ($saleval) {
        $saleData = $saleval->toArray();

        foreach ($saleData as $pur) {
            $sales[] = [
               'firstname' => $pur['user']['firstname'],
                'officeid' => $pur['user']['officeid'],
                'rname' => $pur['retailer']['name'],
                 'rid' => $pur['retailer']['officeid'],
                'sno' => $pur['sno'],
                'imei' => $pur['imei'],
                'brand' => $pur['brand']['name'],
                'name' => $pur['product']['name'],
                'model' => $pur['product']['model'],
                'created_at' => $pur['created_at'],
            ];
        }
    } 
   

}
//--------------------------------------------------
//dd(Session::all());
//dd($purchases);
//Session::forget(['user_id','fdate','todate']);
// dd($purchases);
// exit();

  
    return view('tso.dailySalesReports',[ 'upazilas'=>$upazilas,'ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'sales' => $sales]);



  }

public function DailySalesReportViewsStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['upazila_id','fdate','todate','all_report']);


if ($request->get('all_report') == null) {
    
    $this->validate($request, [
      'upazila_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required',
    ]);

    $upazila_id = $request->get('upazila_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');

    Session::put(['upazila_id'=>$upazila_id,'fdate'=>$fdate,'todate'=>$todate]);
}
else{


    $this->validate($request, [
      'fdate' => 'required',
      'todate' => 'required',
      'all_report' => 'required'
    ]);
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    //$all_report = $request->get('all_report');
    Session::put(['all_report'=>$all_report,'fdate'=>$fdate,'todate'=>$todate]);
}

  return redirect(route('tso.dailySalesReports'));


  }

  //================DistributorImeiStockReportView=======================

  public function DistributorImeiStockReportView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}


     $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

     $queryresults=[];
   
foreach ($upazilas as $key => $upazila) {
    if (isset($upazila['upazila_id'])) {
        $upazila_id = $upazila['upazila_id'];

        $query = DB::table('purchases as t1')
            ->select('t1.sno as sno', 't1.imei as imei',
                't2.firstname as distributor', 't2.officeid as officeid', 't4.name as brand',
                't3.name as product', 't3.model as model')
            ->join('users as t2', 't1.user_id', '=', 't2.id')
            ->join('brands as t4', 't1.brand_id', '=', 't4.id')
            ->join('products as t3', 't1.product_id', '=', 't3.id')
            ->whereNotIn('t1.sno', function ($query) {
                $query->select('sno')->from('sales');
            })
            ->where('user_id', $upazila_id)
            ->orderBy('t1.id', 'desc')
            ->get();

        if (!$query->isEmpty()) {
            $dstock = $query->toArray();

            foreach ($dstock as $pur) {
                $queryresults[] = [
                    'distributor' => $pur->distributor,
                    'officeid' => $pur->officeid,
                    'sno' => $pur->sno,
                    'imei' => $pur->imei,
                    'brand' => $pur->brand,
                    'product' => $pur->product,
                    'model' => $pur->model,
                ];
            }
        }
    }
}
    return view('tso.distributorImeiStock',['queryresults'=>$queryresults]);

  }


  //================RetailerImeiStockReportView=======================

  public function RetailerImeiStockReportView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
        $upazilaResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $upazilas = $upazilaResult->toArray();

     $queryresults=[];
   
foreach ($upazilas as $key => $upazila) {
    if (isset($upazila['upazila_id'])) {
        $upazila_id = $upazila['upazila_id'];



    $query = DB::table('sales as t1')
        ->select('t1.sno as sno','t1.imei as imei',
          't2.firstname as retailer','t2.officeid as retofficeid',
          't4.firstname as distributor','t4.officeid as distofficeid','t5.name as brand',
          't3.name as product','t3.model as model')
        ->join('users as t2', 't1.ruser_id', '=', 't2.id')
        ->join('brands as t5', 't1.brand_id', '=', 't5.id')
        ->join('products as t3', 't1.product_id', '=', 't3.id')
        ->join('users as t4', 't1.user_id', '=', 't4.id')
        ->whereNotIn('t1.sno',function($query){
            $query->select('sno')->from('smsdetails');
          })
         ->where('user_id',$upazila_id)
        ->orderBy('t1.id','desc')
        //->take(10)
        ->get();
      //->paginate(5)
   if (!$query->isEmpty()) {
            $rstock = $query->toArray();

            foreach ($rstock as $pur) {
                $queryresults[] = [
                    'distributor' => $pur->distributor,
                    'distofficeid' => $pur->distofficeid,
                    'retailer' => $pur->retailer,
                    'retofficeid' => $pur->retofficeid,
                    'sno' => $pur->sno,
                    'imei' => $pur->imei,
                    'brand' => $pur->brand,
                    'product' => $pur->product,
                    'model' => $pur->model,
                ];
            }
        }

    //dd($queryresults);

}
 }
    return view('tso.retailerImeiStock',['queryresults'=>$queryresults]);

 

}

// WOD Report===============


  public function WodView(){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}
    //$userCount = User::count();
       $distributorResult = Tsoupazila::select('id','name','bn_name','upazila_id')->where(['user_id'=>Auth::id()])->orderBy('id','desc')->get();
    $distributors = $distributorResult->toArray();

     $queryresults=[];

    // $distributorResult = User::where('level',100)->orderBy('id','desc')->get();
    // $distributors = $distributorResult->toArray();

  $brandResult = Brand::orderBy('id','desc')->get();
    $brands = $brandResult->toArray();

  $productResult = Product::orderBy('id','desc')->get();
    $products = $productResult->toArray();

    // $retailerResult = Retailer::orderBy('id','desc')->get();
    // $retailers = $retailerResult->toArray();

    // $srResult = Sr::orderBy('id','desc')->get();
    // $srs = $srResult->toArray();


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
  if($fdate && $todate && $brand_id && $distributor_id){
//--------------------------------------------------
    $ssdata['count'] = 1;
    //$ssdata['type'] = $type;
    $ssdata['fdate'] = $fdate;
    $ssdata['todate'] = $todate;
//--------------------------------------------------
   
  
      //with distributor_id all===========


    
          // $sales = Sale::with('product','retailer','user','sr','district','upazila','brand')->select('id','user_id','product_id',count('retailer_id') as rcount,'dis_id','up_id','sr_id','brand_id','sno','imei',sum('quantity') as qty,'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->groupBy('product_id')->get();

if($brand_id =="All" && $distributor_id =="All" ){

  foreach ($distributors as $key => $distributor) {
    $distributor_id = $distributor['upazila_id']; 

    $query = DB::table('sales as t1')
      ->select((DB::raw("SUM(t1.quantity) as qty")),(DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
        't3.name as product','t3.model as model','t2.firstname as distributor','t2.officeid as officeid',
        't4.name as brand', 't5.name as category'
        
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')

      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->join('cats as t5', 't3.cat_id', '=', 't5.id')
       ->join('users as t2', 't1.user_id', '=', 't2.id')
      //->where('t1.brand_id', $brand_id )
       ->where('t1.user_id', $distributor_id )
      //->count('t1.retail_id as retailqty')
       ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])
      
      ->groupBy('t1.product_id')
      ->get();
    //->paginate(5)
  //$wod = json_decode(json_encode($query), True);

   if (!$query->isEmpty()) {
            $wd = $query->toArray();

            foreach ($wd as $pur) {
                $wod[] = [
                    'distributor' => $pur->distributor,
                    'officeid' => $pur->officeid,
                    'rqty' => $pur->rqty,
                    'category' => $pur->category,
                    'qty' => $pur->qty,
                    'brand' => $pur->brand,
                    'product' => $pur->product,
                    'model' => $pur->model,
                ];
            }
        }


      //    $query = Sale::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
    //         ->where(['user_id'=>$distributor_id,'product_id'=>$product_id])
    //         ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
    //         ->groupBy('product_id')
    //         ->first();
    //     $sales = $query->toArray();

    //     $data2[$key] = $sales['quantity'];
      // //with distributor_id all===========
 }    
}

elseif($brand_id =="All"){
 $query = DB::table('sales as t1')
      ->select((DB::raw("SUM(t1.quantity) as qty")),(DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
        't3.name as product','t3.model as model','t2.firstname as distributor','t2.officeid as officeid',
        't4.name as brand', 't5.name as category'
        
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')

      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->join('cats as t5', 't3.cat_id', '=', 't5.id')
      ->join('users as t2', 't1.user_id', '=', 't2.id')
      //->where('t1.brand_id', $brand_id )
      ->where('t1.user_id', $distributor_id )
      
      //->count('t1.retail_id as retailqty')
       ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])
      
      ->groupBy('t1.product_id')
      ->get();
    //->paginate(5)
  $wod = json_decode(json_encode($query), True);


  
}
elseif($distributor_id =="All"){

foreach ($distributors as $key => $distributor) {
    $distributor_id = $distributor['upazila_id']; 


 $query = DB::table('sales as t1')
      ->select((DB::raw("SUM(t1.quantity) as qty")),(DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
        't3.name as product','t3.model as model','t2.firstname as distributor','t2.officeid as officeid',
        't4.name as brand', 't5.name as category'
        
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')

      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->join('cats as t5', 't3.cat_id', '=', 't5.id')
      ->join('users as t2', 't1.user_id', '=', 't2.id')
      ->where('t1.brand_id', $brand_id )
      ->where('t1.user_id', $distributor_id )
      
      //->count('t1.retail_id as retailqty')
       ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])
      
      ->groupBy('t1.product_id')
      ->get();
    //->paginate(5)
  // $wod = json_decode(json_encode($query), True);


   if (!$query->isEmpty()) {
            $wd = $query->toArray();

            foreach ($wd as $pur) {
                $wod[] = [
                    'distributor' => $pur->distributor,
                    'officeid' => $pur->officeid,
                    'rqty' => $pur->rqty,
                    'category' => $pur->category,
                    'qty' => $pur->qty,
                    'brand' => $pur->brand,
                    'product' => $pur->product,
                    'model' => $pur->model,
                ];
            }
        }

}
  
}
else{
 $query = DB::table('sales as t1')
      ->select((DB::raw("SUM(t1.quantity) as qty")),(DB::raw("count(DISTINCT t1.ruser_id) as rqty")),
        't3.name as product','t3.model as model','t2.firstname as distributor','t2.officeid as officeid',
        't4.name as brand', 't5.name as category'
        
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')

      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->join('cats as t5', 't3.cat_id', '=', 't5.id')
       ->join('users as t2', 't1.user_id', '=', 't2.id')
      ->where('t1.brand_id', $brand_id )
      ->where('t1.user_id', $distributor_id )
      
      //->count('t1.retail_id as retailqty')
       ->whereBetween(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d')"), [$fdate, $todate])
      
      ->groupBy('t1.product_id')
      ->get();
    //->paginate(5)
  $wod = json_decode(json_encode($query), True);


  
}


}




//Session::forget(['user_id','fdate','todate']);

    return view('tso.wod',['ssdata'=>$ssdata,'dailyPurchaseSaleReport1s'=>$dailyPurchaseSaleReport1s,'distributors' => $distributors,'brands' => $brands,'sales'=>$wod]);

  }


  public function WodViewStore(Request $request){
    if (Auth::user()->level != 10) { return redirect()->route('logout');}

    //dd($request->all());

    Session::forget(['fdate','todate','all_report']);


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

    Session::put(['fdate'=>$fdate,'todate'=>$todate, 'brand_id'=>$brand_id, 'distributor_id'=>$distributor_id]);

  return redirect(route('tso.wod'));


  }



 




}
