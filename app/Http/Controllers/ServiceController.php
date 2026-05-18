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



use App\User;
use App\Setting;
use App\Smsdetail;



/*use App\User;
use App\Product;
use App\ProductCategory;

use App\Region;
use App\Territory;

use App\Distributor;
use App\Quantity;
use App\Distuser;
use App\Invoice;
use App\Invoproduct;
use App\Vat;
use App\Distsale;
use App\Target;
use App\Securitydeposit;

use App\Purchase;
use App\Purchaseproduct;

use App\Preturn;
use App\Preturnproduct;*/

class ServiceController extends Controller
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
    
    //return Auth::user()->level;

    $settingCount = Setting::count();
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
    if (Auth::user()->level != 5) { return redirect()->route('logout');}
  }

  public function DashboardView(){
    if (Auth::user()->level != 5) { return redirect()->route('logout');}

    //return "Hello Service Controller";


    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    return redirect(route('service.wcheckProduct')); 
    return view('service.dashboard');
    
  }

  public function Test(){
    if (Auth::user()->level != 5) { return redirect()->route('logout');}
    return redirect(route('service.wcheckProduct')); 
    return view('service.dashboard');
  }



//================WcheckProduct=======================


  public function WcheckProductView(){
    if (Auth::user()->level != 5) { return redirect()->route('logout');}
    
    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;
    
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
        
        $query = Smsdetail::with('product','replace','user','brand')->select('id','product_id','brand_id','user_id','promo_id','promodetail_id','sno','imei','wperiod','iswar','mobile',
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

    return view('service.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 5) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('service.wcheckProduct'));
  


  }



  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 5) { return redirect()->route('logout');}


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


//-------------------
    $smsdetail->iswar = 0;
    $smsdetail->save(); 
//-------------------
  $request['smsdetail_id'] = $request->id;
  Replace::create($request->all());
//-------------------
    
    return redirect()->back()->with('success', 'Data has been inserted successfully');


  }

//================WcheckProduct=======================







}
