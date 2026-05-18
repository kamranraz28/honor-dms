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


use App\Smsdetail;


class ApiController extends Controller
{

	public function verifyProduct($sno=null){

	if ($sno == null) {
		$returndata = "Serial or IMEI number required";
     	return response()->json($returndata, 200,[],JSON_PRETTY_PRINT);
	}	

	$data = [];
    $scount = Smsdetail::where('sno',$sno)->orWhere('imei',$sno)->count();

    if ($scount > 0) {
      $sdetils = Smsdetail::select('id',
        DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as date')
      )->where('sno',$sno)->orWhere('imei',$sno)->first();

      $data = [
        //'id'=>$sdetils->id,
        'date'=>$sdetils->date,
        'status'=>'Active',
      ];
    }else{
      $data = [
        //'id'=>null,
        'date'=>null,
        'status'=>'Inactive',
      ];
    }
    return response()->json($data,200,[],JSON_PRETTY_PRINT);

	}


}