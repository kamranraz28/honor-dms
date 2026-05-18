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


class VerifyController extends Controller
{
  

  public function __construct(){
    //$this->middleware('auth'));
  }



  public function VerifyPassView($code=null,$email=null,$pass=null){
    if ($code == 5050) {
      $data['active'] = true;
      //$data['level'] = 100;
      $data['email'] = $email;
      $data['password'] = $pass;

      $data1['active'] = true;
      //$data1['level'] = 100;
      $data1['officeid'] = $email;
      $data1['password'] = $pass;

      if (Auth::attempt($data)) {
        return 'true';
      }else if(Auth::attempt($data1)){
        return 'true';
      }else{
        return 'false';
      }

    } else {
      return 'false';
    }

        
    

    
  }


  public function VerifySamsungProductView($sno=null){
    $data = [];
    $dataCount = 0;
    //$sno = Session::get('sno');

    if($sno){
      $dataCount = 1;

$stockCount = Stock::where(['imei' => $sno])->orWhere(['sno'=>$sno])->count();
if ($stockCount > 0) {
  $stockResult = Stock::select('product_id','sno')
            ->where(['imei' => $sno])
            ->orWhere(['sno'=>$sno])
            ->take(1)
            ->first();


  $product_id = $stockResult->product_id;


  $data = Product::with('specification')->select('id','name as product','model','photo','details')->where(['id'=>$product_id])->take(1)->first();
} else {
  $dataCount = 0;
  $data = [];

  Session::flash('error','দুঃখিত এই প্রডাক্টটি অফিসিয়াল ওয়ারেন্টির আওতাভুক্ত নয় ');
}

//Session::forget(['errors']);


//dd($data);

    }


    return view('admin.VerifySamsungProduct', ['data' => $data,'dataCount'=>$dataCount]);
  }

  public function VerifySamsungProductViewStore(Request $request){
    //dd($request->all());

    Session::forget(['sno']);

    $this->validate($request, [
      'sno' => 'required',
    ]);


    //dd($request->all());

    $sno = $request->get('sno');
    
    Session::put(['sno'=>$sno]);


    //dd(Session::all());


    return redirect(route('guest.verifySamsungProduct',[$sno]));
  }




}



