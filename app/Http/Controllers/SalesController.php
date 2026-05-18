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

class SalesController extends Controller
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
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
  }

  public function DashboardView(){
     if (Auth::user()->level != 200) { return redirect()->route('logout');}

    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

     
    return view('sales.dashboard');
    
  }

  public function Test(){
    if (Auth::user()->level != 200) { return redirect()->route('logout');}
    return view('sales.dashboard');
  }












}
