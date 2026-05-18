<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests\ClassName;
//use App\Http\Controllers\AuthController;
use App\Models\Orderspostingdetailsimi;
use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;

use Illuminate\Support\Str;
use App\Jobs\StockBulkUpload;
use App\JobProgress;
use App\Jobs\ImeiBulkUpload;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Redirect;
use Validator;
use Input;
use Session;
use Auth;
use Storage;
use File;
use DB;
use Hash;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;
use League\Csv\Writer;

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

use App\Promort;
use App\Promortdetail;
use App\Promortretailer;
use App\Promortkey;
use App\Promortsmsdetail;

use App\Smsdetail;
use App\Dwdetail;

use App\Replace;
use App\Sr;


use App\Purchase;
use App\Order;
use App\Sale;
use App\Preturn;


use App\Division;
use App\District;
use App\Upazila;
use App\Middistrict;
use App\Tsoupazila;
use App\Models\Ordersposting;
use App\Orderspostingdetail;

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

class WarehouseController extends Controller
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

  private function security()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }
  }

  public function DashboardView()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    //return "Hello warehouse Controller";
    $posting = Ordersposting::all();

    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    //return redirect(route('warehouse.wcheckProduct'));
    return view('warehouse.dashboard')->with(['posting' => $posting]);

  }

  public function Test()
  {
    if (Auth::user()->level != 5) {
      return redirect()->route('logout');
    }
    return redirect(route('warehouse.wcheckProduct'));
    return view('warehouse.dashboard');
  }



  //================WcheckProduct=======================


  public function WcheckProductView()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

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

        $query = Smsdetail::with('product', 'replace', 'user', 'brand')->select(
          'id',
          'product_id',
          'brand_id',
          'user_id',
          'promo_id',
          'promodetail_id',
          'sno',
          'imei',
          'wperiod',
          'iswar',
          'mobile',
          DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
        )

          ->where(['user_id' => Auth::id()])
          ->where(['imei' => $imei])
          ->orWhere(['sno' => $imei])
          //->take(1)
          ->get();

        $data = json_decode(json_encode($query), True);

        //dd($data);

      }

    }




    //Session::forget(['user_id','fdate','todate']);

    return view('warehouse.wcheckProduct', ['ssdata' => $ssdata, 'wcheckProducts' => $data, 'dataCount' => $dataCount]);

  }


  public function WcheckProductViewStore(Request $request)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');

    Session::put(['imei' => $imei]);

    return redirect(route('warehouse.wcheckProduct'));



  }



  public function WcheckProductReplace(Request $request)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }


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


  // Upload1 =======================================

  public function Upload1View()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }



    return view('warehouse.upload1');

  }

  public function Upload1ViewStore(Request $request)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    //$this->validate($request,['type'=>'required']);

    $type = $request->type;
    // dd($type);
    //==================================
    $image = $request->file('csv_file');

    if (!is_null($image)) {

      $this->validate($request, [
        'csv_file' => 'required|mimes:csv,txt|max:200000',
      ]);

      // $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
      //Storage::put($image_name, file_get_contents($image));
      //=================================================================

    }


    /*		if ($type != 5) {
        return redirect()->back()->withErrors('Only bulk stock can be uploaded');
      }*/

    //==================================

    // if ($type == 5) {

    //   /*//======================
    //     $count = Stock::count();
    //     if ($count > 0) {
    //       return redirect()->back()->withErrors("Process can not be completed due to  data has already been stored.")->withInput();
    //     }
    //   //======================*/


    //   // for product specification-------------------

    //   $path = $request->file('csv_file')->getRealPath();

    //   $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);

    //   $data = array_map('str_getcsv', file($path));
    //   $csv_data = array_slice($data, 1, count($row_index));

    //   foreach ($csv_data as $key => $value) {

    //     $productName = $value[0];
    //     $color = $value[1];
    //     $model = $value[0] . '_' . $value[1];
    //     // dd($model);

    //     $pCount = DB::table('products')->select('id')->where(['model' => $model])->count();
    //     // dd($pCount);

    //     //---------------------------------------
    //     if ($pCount > 0) {

    //       $productResult = DB::table('products')->select('id', 'name', 'brand_id')->where(['model' => $model])->take(1)->first();
    //       // dd($productResult);
    //       $productdata = json_decode(json_encode($productResult), True);

    //       $data1['imei'] = $value[3];
    //       $data1['sno'] = $value[2];
    //       $data1['product_id'] = $productdata['id'];
    //       $data1['brand_id'] = $productdata['brand_id'];
    //       //---------------------------------------

    //       //$data1['details'] = $value[6];
    //       $data1['wperiod'] = $value[4];

    //       Stock::create($data1);
    //     }
    //     //---------------------------------------

    //   }
    //   // for product specification-------------------

    //   //====================================================
    //   $pCount = 0;
    //   $pdata = [];

    //   foreach ($csv_data as $key => $value) {



    //     $model = $value[0] . '_' . $value[1];


    //     //---------------------------------------
    //     $pCount = DB::table('products')->select('id')->where(['model' => $model])->count();



    //     if ($pCount < 1) {
    //       $pdata[] = $model;
    //       $pCount += 1;
    //     } else {
    //       $pCount = 0;
    //     }


    //     //---------------------------------------

    //   }
    //   // for product Stock-------------------

    // }
      if ($type == 5) {
            $file = $request->file('csv_file');
            $csvPath = $file->store('upload\stock_uploads', 'local');

            $jobId = (string) Str::uuid();

            // Initialize job tracking
            $job = JobProgress::create([
                'user_id' => Auth::id(),
                'job_id' => $jobId,
                'type' => 'stock_bulk_upload',
                'status' => 'queued',
                'message' => 'Job is queued for processing.',
            ]);

            // Dispatch with jobId
            StockBulkUpload::dispatch($csvPath, $jobId)->onQueue('stock_upload');

            return redirect()->back()->with('success', "Stock upload queued (Job: JB-{$job->id}).");
        }
     else if ($type == 10) {


      // for product stock-------------------

      $path = $request->file('csv_file')->getRealPath();

      $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);

      $data = array_map('str_getcsv', file($path));
      $csv_data = array_slice($data, 1, count($row_index));

      foreach ($csv_data as $key => $value) {



        $distributor = $value[0];
        $sno = $value[1];

        //---------------------------------------
        $count2 = DB::table('purchases')->select('id')->where(['sno' => $sno])->count();
        if ($count2 > 0) {
          return redirect()->back()->withErrors("Duplicate serial no :  $sno can not be taken")->withInput();
        }

        $count = DB::table('users')->select('id')->where(['officeid' => $distributor])->count();

        if ($count < 1) {
          return redirect()->back()->withErrors("There is no user with this dstributor $distributor")->withInput();
        }

        $count1 = DB::table('stocks')->select('id')->where(['sno' => $sno])->count();
        if ($count1 < 1) {
          return redirect()->back()->withErrors("There is no more product in stock with this sno $sno")->withInput();
        }
        //---------------------------------------

      }
      // for product specification-------------------



      foreach ($csv_data as $key => $value) {



        $distributor = $value[0];
        $sno = $value[1];

        //---------------------------------------




        $disresult = DB::table('users')->select('id')->where(['officeid' => $distributor])->take(1)->first();
        $disdata = json_decode(json_encode($disresult), True);

        $disid = $disdata['id'];

        $stockresult = DB::table('stocks')->select('id', 'product_id', 'imei', 'sno', 'brand_id')->where(['sno' => $sno])->take(1)->first();
        $stockdata = json_decode(json_encode($stockresult), True);

        $stock_id = $stockdata['id'];
        $product_id = $stockdata['product_id'];
        $brand_id = $stockdata['brand_id'];
        $imei = $stockdata['imei'];
        $sno = $stockdata['sno'];



        if ($imei == "") {
          $imei = NULL;
        }

        //---------------------------------------


        $data1['stock_id'] = $stock_id;
        $data1['user_id'] = $disid;
        $data1['product_id'] = $product_id;
        $data1['brand_id'] = $brand_id;
        $data1['imei'] = $imei;
        $data1['sno'] = $sno;
        $data1['status'] = 0;


        Purchase::create($data1);
        //---------------------------------------

      }


}


    //  else if ($type == 12) {


    //   // for product stock-------------------

    //   $path = $request->file('csv_file')->getRealPath();

    //   $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);

    //   $data = array_map('str_getcsv', file($path));
    //   $csv_data = array_slice($data, 1, count($row_index));

    //   foreach ($csv_data as $key => $value) {

    //     // dd($value);
    //     $distributor = $value[0];
    //     $sno = $value[1];
    //     $orderid = $value[2];

    //     //---------------------------------------
    //     $count2 = DB::table('purchases')->select('id')->where(['sno' => $sno])->orWhere(['imei' => $sno])->count();
    //     if ($count2 > 0) {
    //       return redirect()->back()->withErrors("Duplicate serial no :  $sno can not be taken")->withInput();
    //     }

    //     $count3 = DB::table('orderspostingdetailsimis')->select('id')->where(['imi' => $sno])->count();
    //     if ($count3 > 0) {
    //       return redirect()->back()->withErrors("Duplicate serial no :  $sno can not be taken")->withInput();
    //     }

    //     $count = DB::table('users')->select('id')->where(['officeid' => $distributor])->count();

    //     if ($count < 1) {
    //       return redirect()->back()->withErrors("There is no user with this dstributor $distributor")->withInput();
    //     }

    //     $count1 = DB::table('stocks')->select('id')->where(['sno' => $sno])->orWhere(['imei' => $sno])->count();
    //     if ($count1 < 1) {
    //       return redirect()->back()->withErrors("There is no more product in stock with this sno $sno")->withInput();
    //     }

    //     $count1 = DB::table('orderspostings')->select('id')->where(['orader_number' => $orderid, 'status' => 1])->orwhere(['orader_number' => $orderid, 'status' => 2])->orwhere(['orader_number' => $orderid, 'status' => 3])->count();
    //     if ($count1 < 1) {
    //       return redirect()->back()->withErrors("There is no order id like $orderid")->withInput();
    //     }
    //     //---------------------------------------

    //   }
    //   // for product specification-------------------







    //   foreach ($csv_data as $key => $value) {



    //     $distributor = $value[0];
    //     $sno = $value[1];
    //     $orderid = $value[2];
    //     //---------------------------------------

    //     // dd($sno);

    //     $disresult = DB::table('users')->select('id')->where(['officeid' => $distributor])->take(1)->first();
    //     $disdata = json_decode(json_encode($disresult), True);

    //     $disid = $disdata['id'];

    //     $stockresult = DB::table('stocks')->select('id', 'product_id', 'imei', 'sno', 'brand_id')->where(['sno' => $sno])->orwhere(['imei' => $sno])->take(1)->first();
    //     $stockdata = json_decode(json_encode($stockresult), True);

    //     $stock_id = $stockdata['id'];
    //     $product_id = $stockdata['product_id'];
    //     $brand_id = $stockdata['brand_id'];
    //     $imei = $stockdata['imei'];
    //     $sno = $stockdata['sno'];


    //     $ordernumber = DB::table('orderspostings')->select('id')->where(['orader_number' => $orderid])->take(1)->first();
    //     $ono = json_decode(json_encode($ordernumber), True);

    //     $orderno = $ono['id'];
    //    // dd($orderno);

    //     $checkp = DB::table('orderspostingdetails')->select('id', 'product_id', 'quantity')->where(['orderspostings_id' => $orderno, 'product_id' => $product_id])->take(1)->first();
    //     $check = json_decode(json_encode($checkp), True);
    //     $p_id = $check['product_id'];
    //     $id = $check['id'];
    //     $quantity = $check['quantity'];


    //     $counto = DB::table('orderspostingdetails')->select('id', 'product_id')->where(['orderspostings_id' => $orderno, 'product_id' => $product_id])->count();
    //     // dd($counto);
    //     $countq = DB::table('orderspostingdetailsimis')->select('id')->where(['orderspostingdetails_id' => $id, 'product_id' => $product_id])->count();
    //     //dd($countq);

    //     if ($counto > 0 && $countq < $quantity) {


    //       DB::table('orders')
    //         ->where('id', $orderid)
    //         ->update(['status' => "3"]);

    //       DB::table('orderspostings')
    //         ->where('orader_number', $orderid)
    //         ->update(['status' => "3"]);

    //       $dataToInsert = [
    //         'imi' => $sno,
    //         'orderspostingdetails_id' => $id,
    //         'product_id' => $product_id,
    //         'created_by' => auth()->user()->id,
    //       ];

    //       // Insert the data into the 'Orderspostingdetailsimi' table
    //       $orderspostingdetail = Orderspostingdetailsimi::create($dataToInsert);



    //     }


    //   }



    // }

else if ($type == 12) {
      $path = $request->file('csv_file')->getRealPath();
      $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
      $data = array_map('str_getcsv', file($path));
      $csv_data = array_slice($data, 1, count($row_index));

      $count = 0;
      $dataToInsert = [];

      foreach ($csv_data as $key => $value) {
          $distributor_id = $value[0];
          $sno = $value[1];
          $orderid = $value[2];

          $orderInfo = Order::with('user')->find($orderid);

          if (!$orderInfo) {
              return redirect()->back()->withErrors("Order number doesn't match.");
          }

          $orderStatus = $orderInfo->status;

          if ($orderStatus !== 1 && $orderStatus !== 2) {
            return redirect()->back()->withErrors("This order is not ready to add IMEI");
          }

          $officeId = $orderInfo->user->officeid;

          if ($officeId !== $distributor_id) {
              return redirect()->back()->withErrors("Distributor Doesn't match for this order.");
          }

          $stockInfo = Stock::where('sno', $sno)->orWhere('imei', $sno)->get();


          if (!$stockInfo || $stockInfo->isEmpty()) {
              return redirect()->back()->withErrors("One or more IMEI is not available in stock.");
          }
          $imei = $stockInfo->pluck('imei')->first();

          $product_id = $stockInfo->pluck('product_id');

          $orderPostingDetailsImeiInfo = Orderspostingdetailsimi::where('imi', $sno)->orWhere('imi2', $sno)->get();

          if ($orderPostingDetailsImeiInfo->isNotEmpty()) {
              return redirect()->back()->withErrors("One or more IMEI has been sold.");
          }

          $orderPostingInfo = Ordersposting::where('orader_number', $orderid)->first();

          $orderPostingId = $orderPostingInfo->id;


          $orderPostingDetailsInfo = Orderspostingdetail::with('orderspostingdetailsimis')->where('orderspostings_id', $orderPostingId);
          $orderPostingDetailsList = $orderPostingDetailsInfo->get();

          $productID = $orderPostingDetailsInfo->pluck('product_id');

          foreach ($product_id as $id) {
            if (!$productID->contains($id)) {
              return redirect()->back()->withErrors("Product doesn't match for this order. Please check your CSV file.");
            }
          }

          $totalQuantity = $orderPostingDetailsInfo->sum('quantity');
          $countExisting = Orderspostingdetailsimi::whereIn('orderspostingdetails_id', $orderPostingDetailsList->pluck('id'))->count();
          $countNew = 0;

          $product_ids = $stockInfo->pluck('product_id')->toArray();
          $user_id = Auth::id();


          foreach ($product_ids as $product_id) {

              $orderPostingDetails = $orderPostingDetailsList->where('product_id', $product_id)->first();

              if ($orderPostingDetails) {
                  $data1 = [
                      'orderspostingdetails_id' => $orderPostingDetails->id,
                      'order_number' => $orderid,
                      'product_id' => $product_id,
                      'imi' => $value[1],
                      'imi2' => $imei,
                      'created_by' => $user_id,
                  ];

                  $dataToInsert[] = $data1;
                  $countNew++;
              }
          }
          $chunkSize = 100;

          if (!empty($dataToInsert)) {

              foreach (array_chunk($dataToInsert, $chunkSize) as $chunk) {
                  Orderspostingdetailsimi::insert($chunk);
              }
          }

          $count++;

          if ($count > 0) {
              $orderid = $value[2];

              $totalCount = $countExisting + $countNew;

              if ($totalCount >= $totalQuantity) {
                  Order::find($orderid)->update(['status' => 3]);
                  Ordersposting::where('orader_number', $orderid)->update(['status' => 3]);
              } else {
                  Order::find($orderid)->update(['status' => 2]);
                  Ordersposting::where('orader_number', $orderid)->update(['status' => 2]);
              }
          }
      }
  }


    return redirect()->back()->with('success', 'Data has been inserted successfully');

  }

  // Stock =======================================
  public function StockViews()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }


    //$stockResult = Stock::with('territory')->get();
    $stocks = Stock::select('id', 'product_id', 'brand_id', 'imei', 'sno', 'wperiod', 'created_at')->with('product', 'brand')->orderBy('id', 'desc')->paginate(500);
    //$stocks = $stockResult->toArray();

    $productResult = Product::orderBy('id', 'desc')->get();
    $products = $productResult->toArray();


    return view('warehouse.stocktable', ['stocks' => $stocks, 'products' => $products]);

  }

  public function StockView()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }


    //$stockResult = Stock::with('territory')->get();
    $stocks = Stock::select('id', 'product_id', 'brand_id', 'imei', 'sno', 'wperiod', 'created_at')->with('product', 'brand')->orderBy('id', 'desc')->paginate(500);
    //$stocks = $stockResult->toArray();

    $productResult = Product::orderBy('id', 'desc')->get();
    $products = $productResult->toArray();


    return view('warehouse.stock', ['stocks' => $stocks, 'products' => $products]);

  }

  public function StockViewExcel()
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }


    // return Excel::download(new StocksExport, 'stocks.xlsx');


    $query = DB::table('stocks as t1')
      ->select(
        't3.name as product',
        't3.model as model',
        't1.sno as sno',
        't1.imei as imei',
        't4.name as brand',
        't1.wperiod as wperiod',
        DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m-%d') as Date")
      )
      //->join('brands as t2', 't1.brand_id', '=', 't2.id')
      ->join('products as t3', 't1.product_id', '=', 't3.id')
      ->join('brands as t4', 't1.brand_id', '=', 't4.id')
      ->orderBy('t1.id', 'desc')
      ->take(10000000)
      ->get();
    //->paginate(5)
    $stocks = json_decode(json_encode($query), True);

    return (new FastExcel($stocks))->download('stocks.xlsx');


  }



  public function StockViewStore(Request $request)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    $this->validate($request, ['product_id' => 'required']);

    $product_id = $request['product_id'];
    $imeis = $request['imeis'];
    $snos = $request['snos'];
    $wperiods = $request['wperiods'];

    //=============
    $query = Product::select('id', 'brand_id')->where('id', $product_id)->first();
    $queryresults = json_decode(json_encode($query), True);
    $brand_id = $queryresults['brand_id'];
    //=============

    if ($imeis == null || $snos == null) {
      return redirect()->back()->withErrors("Please select add more option")->withInput();
    }


    foreach ($imeis as $key => $imei) {
      if ($imei == null) {
        return redirect()->back()->withErrors("Please select IMEI 2 No")->withInput();
      }

      if ($snos[$key] == null) {
        return redirect()->back()->withErrors("Please select IMEI 1 No")->withInput();
      }




    }

    foreach ($imeis as $key => $imei) {


      $request['product_id'] = $product_id;
      $request['imei'] = $imei;
      $request['sno'] = $snos[$key];
      $request['wperiod'] = $wperiods;
      $request['brand_id'] = $brand_id;

      Stock::create($request->all());

    }


    //dd(count($imeis));





    return redirect()->back()->with('success', 'Data has been inserted successfully');


  }

  public function StockUpdate(Request $request)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }
    $id = $request->get('id');
    $stock = Stock::find($id);

    if ($stock === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    } else {
      $this->validate($request, ['product_id' => 'required', 'imei' => 'required', 'sno' => 'required', 'wperiod' => 'required']);
      $stock->product_id = $request->get('product_id');
      $stock->imei = $request->get('imei');
      $stock->sno = $request->get('sno');
      $stock->wperiod = $request->get('wperiod');
      $stock->save();
      return redirect()->back()->with('success', 'IMEI has been provided successfully');
    }


  }

  public function StockDestroy($id)
  {
    if (Auth::user()->level != 6) {
      return redirect()->route('logout');
    }

    $stock = Stock::find($id);

    $productCount = 0;
    //$productCount = Product::where('stock_id', $id)->count();
    //$product = Product::where('stock_id', $id)->get();

    if ($stock === null) {
      return redirect()->back()->withErrors('There are no data with this id');

    } else {
      if ($productCount > 0) {
        return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
      } else {
        $stock->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }



  }


  public function orderReport()
  {

    $order_id = Session::get('distributor_id');

    if ($order_id) {
      $orderNumbers = Ordersposting::where('orader_number', $order_id)->where('status', 5)->get();

      if ($orderNumbers->isNotEmpty()) {
        $orderReports = [];

        foreach ($orderNumbers as $orderNumber) {
          $orderDate = $orderNumber->created_at;
          $orderId = $orderNumber->id;

          $orderList = Orderspostingdetail::where('orderspostings_id', $orderId)->get();
          // dd($orderList);
          $orderDetailsIds = $orderList->pluck('id');
          // dd($orderDetailsIds);

          $orderImeiDetails = Orderspostingdetailsimi::whereIn('orderspostingdetails_id', $orderDetailsIds)->get();
          // dd($orderImeiDetails);
          $orderImeis = $orderImeiDetails->pluck('imi');
          // dd($orderImeis);

          $productDetails = Purchase::whereIn('imei', $orderImeis)
            ->orWhereIn('sno', $orderImeis)
            ->get();
          // dd($productDetails);
          $userIds = $productDetails->pluck('user_id');
          // dd($userIds);
          $productIds = $productDetails->pluck('product_id');
          // dd($productIds);

          $productInfo = Product::whereIn('id', $productIds)->get();
          // dd( $productInfo);

          $userDetails = User::whereIn('id', $userIds)->first();
          // dd($userDetails);

          $count = count($productIds);

          for ($i = 0; $i < $count; $i++) {
            $productId = $productIds[$i];
            $orderImei = $orderImeis[$i]; {
              $product = $productInfo->where('id', $productId)->first();

              $imeis = $productDetails->filter(function ($product) use ($orderImei) {
                return $product->imei == $orderImei || $product->sno == $orderImei;
              })->first();

              // dd($imeis);


              $orderReports[] = [
                'orderNumber' => $order_id,
                'imei' => $imeis->imei,
                'sno' => $imeis->sno,
                'name' => $userDetails->firstname,
                'userId' => $userDetails->officeid,
                'productName' => $product->name,
                'productModel' => $product->model,
                'date' => $orderDate
              ];
            }
          }
        }

        return view('warehouse.orderReport', ['orderReports' => $orderReports]);
      } else {
        return view('warehouse.orderReport');
      }
    }

    // If $order_id is not set
    return view('warehouse.orderReport');


  }



  public function orderReportStore(Request $request)
  {
    $distributor_id = $request->get('distributor_id');

    Session::put(['distributor_id' => $distributor_id]);
    return redirect(route('warehouse.orderReport'));
  }


  public function printinvoice($id)
  {

    $ordersposting = Ordersposting::find($id);
    //return view('orderspostingdetailsimi.deliverychalan', compact('ordersposting'));
    $pdf = PDF::loadView('ordersposting.pdf', compact('ordersposting'), [], [
      'margin_top' => 20,
      'margin_bottom' => 15,
      'margin_left' => 18,
      'margin_right' => 18,
      'format' => 'A4',
      'default_font_size' => '12',
    ]);
    return $pdf->stream('Invoice_' . '.pdf', 'I');
  }


  public function varifyimeino($sno)
  {

    $count = Stock::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();

    $count1 = Orderspostingdetailsimi::select('id')->where(['imi' => $sno])->orWhere(['imi2' => $sno])->count();

    $count2 = Purchase::select('id')->where('sno', $sno)->orWhere('imei', $sno)->count();

    if($count2 > 0){
      return 1;
    }

    if ($count > 0) {
      if ($count1 > 0) {
        return 'IMEI has been sold already.';
      } else {
        $stockdatas = Stock::with('product')->select('product_id')->where('sno', $sno)->orWhere('imei', $sno)->first();

        $model = $stockdatas->product['model'];

        return $model;
      }
    } else {
      return 'IMEI not available in stock.';
    }
  }



  public function distributorDeliveryReport()
  {
    return view('warehouse.distributorDeliveryReport');
  }


    public function distributorDeliveryReportStore(Request $request)
{
    $distributor_id = $request->input('distributor_id');
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');

    $ordersQuery = Order::where('status', 5)
        ->with('user')
        ->whereHas('user', function ($q) {
            $q->where('level', 100);
        });

    if ($distributor_id) {
        $ordersQuery->whereHas('user', function ($q) use ($distributor_id) {
            $q->where('id', $distributor_id);
        });
    }

    if ($fdate && $todate) {
        $ordersQuery->whereBetween(
            'updated_at',
            [
                Carbon::parse($fdate)->startOfDay(),
                Carbon::parse($todate)->endOfDay()
            ]
        );
    }

    $orders = $ordersQuery->get();

    if ($orders->isEmpty()) {
        return (new FastExcel([]))->download('Distributor_Delivery_Report.xlsx');
    }

    // -----------------------------------
    // Fetch postings in ONE go
    // -----------------------------------
    $orderIds = $orders->pluck('id')->toArray();

    $orderPostings = Ordersposting::whereIn('orader_number', $orderIds)->get();

    $postingIds = $orderPostings->pluck('id')->toArray();

    // -----------------------------------
    // Fetch posting details with products
    // -----------------------------------
    $postingDetails = Orderspostingdetail::with('products')
        ->whereIn('orderspostings_id', $postingIds)
        ->get();

    // -----------------------------------
    // Prepare Excel rows
    // -----------------------------------
    $exportData = [];
    $sl = 1;

    foreach ($postingDetails as $detail) {

        $posting = $orderPostings->where('id', $detail->orderspostings_id)->first();
        $order   = $orders->where('id', $posting->orader_number)->first();

        $exportData[] = [
            'Sl' => $sl++,
            'Order Number' => $posting->orader_number ?? '-',
            'LD Code' => optional($order->user)->officeid ?? '-',
            'LD Name' => optional($order->user)->firstname ?? '-',
            'Product Name' => optional($detail->products)->name ?? '-',
            'Product Model' => optional($detail->products)->model ?? '-',
            'Date' => optional($order->updated_at)->format('Y/m/d'),
            'Qty' => $detail->quantity ?? 0,
            'Value' => ($detail->quantity * $detail->price) ?? 0,
        ];
    }

    $fileName = 'Distributor_Delivery_Report_' . date('Ymd_His') . '.xlsx';

    return (new FastExcel($exportData))->download($fileName);
}


  public function deliveryReport()
  {

      return view('warehouse.deliveryReport');
  }


public function deliveryReportStore(Request $request)
{
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    $distributor_id = $request->input('distributor_id');
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    $brand_id = $request->input('brand_id');

    $rows = [];
    $sl = 1;

    $query = Order::where('status', 5)
        ->with('user:id,firstname')
        ->whereHas('user', function ($q) {
            $q->where('level', 100);
        });

    if ($fdate && $todate) {
        $query->whereBetween(DB::raw('DATE(updated_at)'), [$fdate, $todate]);
    }

    if ($distributor_id) {
        $query->where('user_id', $distributor_id);
    }

    // ✅ CHUNK ORDERS (KEY OPTIMIZATION)
    $query->chunk(500, function ($orders) use (&$rows, &$sl, $brand_id) {

        $orderIDs = $orders->pluck('id');

        $postings = Ordersposting::whereIn('orader_number', $orderIDs)
            ->select('id', 'orader_number')
            ->get()
            ->keyBy('orader_number');

        foreach ($orders as $order) {

            if (!isset($postings[$order->id])) {
                continue;
            }

            $postingId = $postings[$order->id]->id;

            $detailsQuery = Orderspostingdetail::where('orderspostings_id', $postingId)
                ->select('quantity', 'price')
                ->with(['Product.brand:id']);

            if ($brand_id && $brand_id !== 'All') {
                $detailsQuery->whereHas('Product.brand', function ($q) use ($brand_id) {
                    $q->where('id', $brand_id);
                });
            }

            $details = $detailsQuery->get();

            if ($details->isEmpty()) {
                continue;
            }

            $totalQty = 0;
            $totalValue = 0;

            foreach ($details as $detail) {
                $totalQty += $detail->quantity;
                $totalValue += $detail->quantity * $detail->price;
            }

            $rows[] = [
                'Sl'          => $sl++,
                'Order Number'    => $order->id,
                'LD Code' => $order->user ? $order->user->officeid : '-',
                'LD Name'     => $order->user ? $order->user->firstname : '-',
                'Date'        => Carbon::parse($order->updated_at)->format('Y-m-d'),
                'Total Qty'   => $totalQty,
                'Total Value' => $totalValue,
            ];
        }
    });

    if (empty($rows)) {
        return back()->with('error', 'No data found');
    }

    return (new FastExcel($rows))
        ->download('delivery_report_' . date('Ymd_His') . '.xlsx');
}


  public function stockReceiveReport()
  {
    return view('warehouse.stockReceiveReport');
  }

public function stockReceiveReportStore(Request $request)
{
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    $fdate = $request->input('fdate');
    $todate = $request->input('todate');

    $query = Stock::join('products', 'stocks.product_id', '=', 'products.id')
        ->select(
            'products.name',
            'products.color',
            DB::raw('COUNT(stocks.id) as qty')
        )
        ->groupBy('stocks.product_id', 'products.name', 'products.color');

    if ($fdate && !$todate) {
        $query->where('stocks.created_at', '>=', $fdate . ' 00:00:00');
    }

    if (!$fdate && $todate) {
        $query->where('stocks.created_at', '<=', $todate . ' 23:59:59');
    }

    if ($fdate && $todate) {
        if ($fdate === $todate) {
            $query->whereBetween('stocks.created_at', [
                $fdate . ' 00:00:00',
                $todate . ' 23:59:59'
            ]);
        } else {
            $query->whereBetween('stocks.created_at', [
                $fdate . ' 00:00:00',
                $todate . ' 23:59:59'
            ]);
        }
    }

    $sl = 1;

    return (new FastExcel($query->cursor()))
        ->download('stock_receive_report_' . date('Ymd_His') . '.xlsx', function ($row) use (&$sl) {

            return [
                'SL'           => $sl++,
                'Product Name' => $row->name,
                'Color'        => $row->color,
                'Qty'          => (int) $row->qty,
            ];
        });
}



//   public function stockDeliveryReport()
// {
//     $fdate = Session::get('fdate');
//     $todate = Session::get('todate');
//     $stockDeliveryReport = [
//       'productModel' => [],
//       'productDetailsWithCounts' => [],
//   ];

//     if ($fdate == null && $todate == null) {
//       $deliveredStocks = [];
//     } elseif ($fdate !== null && $todate === null) {
//         $timestamp = strtotime($fdate);

//         $deliveredStocks = Purchase::with('product')
//             ->when($fdate, function ($query) use ($fdate, $timestamp) {
//                 return $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
//             })
//             ->get();
//     } elseif ($fdate == null && $todate !== null) {
//         $timestamp = strtotime($todate);

//         $deliveredStocks = Purchase::with('product')
//             ->when($todate, function ($query) use ($todate, $timestamp) {
//                 return $query->where('created_at', '<=', date('Y-m-d 23:59:59', $timestamp));
//             })
//             ->get();
//     } elseif ($fdate !== null && $todate !== null) {
//         $timestampFdate = strtotime($fdate);
//         $timestampTodate = strtotime($todate);

//         $deliveredStocks = Purchase::with('product')
//             ->when($fdate && $todate, function ($query) use ($timestampFdate, $timestampTodate) {
//                 return $query->when($timestampFdate == $timestampTodate, function ($query) use ($timestampFdate, $timestampTodate) {
//                     return $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestampFdate))
//                                  ->where('created_at', '<=', date('Y-m-d 23:59:59', $timestampTodate));
//                 }, function ($query) use ($timestampFdate, $timestampTodate) {
//                     return $query->whereBetween('created_at', [
//                         date('Y-m-d 00:00:00', $timestampFdate),
//                         date('Y-m-d 23:59:59', $timestampTodate)
//                     ]);
//                 });
//             })
//             ->get();
//     }

//     if ($deliveredStocks) {
//         $productDetailsWithCounts = $deliveredStocks->pluck('product')->groupBy('model')->map(function ($group) {
//             return [
//                 'count' => count($group),
//                 'name' => $group->first()->name,
//                 'color' => $group->first()->color,
//             ];
//         });

//         $productModel = $productDetailsWithCounts->keys()->toArray();

//         $stockDeliveryReport = [
//             'productModel' => $productModel,
//             'productDetailsWithCounts' => $productDetailsWithCounts,
//         ];
//     }

//     return view('warehouse.stockDeliveryReport', compact('stockDeliveryReport'));
// }

public function stockDeliveryReport()
{
    return view('warehouse.stockDeliveryReport');
}


public function stockDeliveryReportStore(Request $request)
{
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    $brand_id = $request->input('brand_id');

    if (!$fdate || !$todate) {
        return back()->with('error', 'Date range is required');
    }

    $query = Orderspostingdetail::join('orderspostings', 'orderspostingdetails.orderspostings_id', '=', 'orderspostings.id')
        ->join('orders', 'orderspostings.orader_number', '=', 'orders.id')
        ->join('products', 'orderspostingdetails.product_id', '=', 'products.id')
        ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
        ->where('orders.status', 5)
        ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$fdate, $todate])
        ->select(
            'products.model',
            'products.color',
            DB::raw('SUM(orderspostingdetails.quantity) as qty')
        )
        ->groupBy('products.id', 'products.model', 'products.color');

    if ($brand_id && $brand_id !== 'All') {
        $query->where('products.brand_id', $brand_id);
    }

    $sl = 1;

    /*
     |--------------------------------------------------------------------------
     | FastExcel Streaming Download
     |--------------------------------------------------------------------------
     */
    return (new FastExcel($query->cursor()))
        ->download('stock_delivery_report_' . date('Ymd_His') . '.xlsx', function ($row) use (&$sl) {

            return [
                'SL'           => $sl++,
                'Product Name' => $row->model ?: 'N/A',
                'Color'        => $row->color ?: 'N/A',
                'Qty'          => (int) $row->qty,
            ];
        });
}



//   public function currentStockReport()
// {
//     $todate = Session::get('todate');
//     $currentStockReport = [];

//     if ($todate !== null) {
//         $timestamp = strtotime($todate);

//         $totalIds = Stock::select('id')
//             ->when($todate, function ($query) use ($todate, $timestamp) {
//                 return $query->where('created_at', '<=', date('Y-m-d 00:00:00', $timestamp));
//             })
//             ->get()
//             ->pluck('id')
//             ->toArray();

//         $deliveredIds = Purchase::select('stock_id')
//             ->when($todate, function ($query) use ($todate, $timestamp) {
//                 return $query->where('created_at', '<=', date('Y-m-d 00:00:00', $timestamp));
//             })
//             ->get()
//             ->pluck('stock_id')
//             ->toArray();
//     } else {
//         $totalIds = [];
//         $deliveredIds = [];
//     }

//     $currentIds = array_diff($totalIds, $deliveredIds);

//     $currentStocks = Stock::find($currentIds);

//     $productDetailsWithCounts = $currentStocks->pluck('product')->groupBy('model')->map(function ($group) {
//         return [
//             'count' => count($group),
//             'name' => $group->first()->name,
//             'color' => $group->first()->color,
//         ];
//     });

//     $productModel = $productDetailsWithCounts->keys()->toArray();

//     $currentStockReport = [
//         'productModel' => $productModel,
//         'productDetailsWithCounts' => $productDetailsWithCounts,
//     ];

//     return view('warehouse.currentStockReport', compact('currentStockReport'));
// }

  public function currentStockReport()
{
    return view('warehouse.currentStockReport');
}


public function currentStockReportStore(Request $request)
{
    ini_set('memory_limit', '1024M');
    set_time_limit(0);

    $todate = $request->input('todate');

    $timestamp = $todate ? strtotime($todate) : null;
    $date = $timestamp ? date('Y-m-d 23:59:59', $timestamp) : null;

    /*
     |--------------------------------------------------------------------------
     | Optimized Current Stock Query
     |--------------------------------------------------------------------------
     */
    $query = Stock::leftJoin('purchases', 'stocks.id', '=', 'purchases.stock_id')
        ->join('products', 'stocks.product_id', '=', 'products.id')
        ->select(
            'products.model',
            'products.name',
            'products.color',
            DB::raw('COUNT(stocks.id) as qty')
        );

    if ($timestamp) {
        $query->where('stocks.created_at', '<=', $date)
              ->where(function ($q) use ($date) {
                  $q->whereNull('purchases.stock_id')
                    ->orWhere('purchases.created_at', '>', $date);
              });
    } else {
        $query->whereNull('purchases.stock_id');
    }

    $query->groupBy('products.model', 'products.name', 'products.color');

    $sl = 1;

    /*
     |--------------------------------------------------------------------------
     | FastExcel Streaming Download
     |--------------------------------------------------------------------------
     */
    return (new FastExcel($query->cursor()))
        ->download('current_stock_report_' . date('Ymd_His') . '.xlsx', function ($row) use (&$sl) {

            return [
                'SL'            => $sl++,
                'Product Model' => $row->name ?: 'N/A',
                'Color'         => $row->color ?: 'N/A',
                'Qty'           => (int) $row->qty,
            ];
        });
}




  public function distributorDeliveryDownload()
  {
    $orders = Order::where('status', 5)->get();
    $orderPosings = Ordersposting::whereIn('orader_number', $orders->pluck('id'))->get();

    $orderDetails = [];

    foreach ($orderPosings as $orderPosing) {
      $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosing->id)->with('products')->get();

      foreach ($orderPostingDetailInfo as $detailInfo) {
        $orderDetails[] = [
          'customerName' => $orders->where('id', $orderPosing->orader_number)->pluck('user.firstname')->first(),
          'productModel' => $detailInfo->products->model,
          'quantity' => $detailInfo->quantity,
          'deliveryDate' => $orders->where('id', $orderPosing->orader_number)->pluck('updated_at')->map(function ($date) {
            return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('Y/m/d');
          })->first(),
          'orderNumber' => $orderPosing->orader_number,
          'price' => $detailInfo->quantity * $detailInfo->price,
        ];
      }
    }

    $excelData = collect($orderDetails)->map(function ($orderDetail) {
      return [
        'Order Number' => $orderDetail['orderNumber'],
        'LD Name' => $orderDetail['customerName'],
        'Product Model' => $orderDetail['productModel'],

        'Delivery Date' => $orderDetail['deliveryDate'],
        'Quantity' => $orderDetail['quantity'],
        'Price' => $orderDetail['price'],
      ];
    });

    return (new FastExcel($excelData))->download('distributor_delivery_report.xlsx');
  }

  public function deliveryDownload()
{
    $orders = Order::where('status', 5)->get();

    $orderIDs = $orders->pluck('id');
    $orderDistributorName = $orders->pluck('user.firstname');
    $orderDate = $orders->pluck('updated_at')->transform(function ($date) {
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    });

    $orderPosings = Ordersposting::whereIn('orader_number', $orderIDs)->get();
    $orderPostingID = $orderPosings->pluck('id');

    $orderPostingDetailInfo = Orderspostingdetail::whereIn('orderspostings_id', $orderPostingID)->with('products')->get();

    $deliveryReport = [];
    foreach ($orderIDs as $key => $id) {
        $details = $orderPostingDetailInfo->where('orderspostings_id', $orderPostingID[$key]);


        $totalQuantity = $details->sum('quantity');
        $totalPrice = $details->sum(function($detail) {
            return $detail->quantity * $detail->price;
        });

        $deliveryReport[] = [
            'Order ID' => $id,
            'LD Name' => $orderDistributorName[$key],
            'Date' => $orderDate[$key],
            'Total Quantity' => $totalQuantity,
            'Total Value' => $totalPrice,
        ];
    }

    return (new FastExcel($deliveryReport))->download('delivery_report.xlsx');
}


  // Stock =======================================

  public function formatDownload($id)
{
    $orderdetails = Orderspostingdetail::where('orderspostings_id', $id)->get();
    $postingDetails = Ordersposting::find($id);
    $order_number = $postingDetails->orader_number;
    $orderInfo = Order::find($order_number);
    $distributor_id = $orderInfo->upazila_id;
    $distributorInfo = User::find($distributor_id);
    $distributorCode = $distributorInfo->officeid;

    $csvData = [];

    foreach ($orderdetails as $orderdetail) {
        $quantity = $orderdetail->quantity;
        $model = $orderdetail->products->model;
        $color = $orderdetail->products->color;

        $count = 1;

        for ($i = 1; $i <= $quantity; $i++) {
            $csvData[] = [
                'LD CODE' => $distributorCode,
                'SL' => $count++,
                'Model' => $model,
                'Color' => $color,
                'IMEI' => '',
                'Order Number' => $order_number,
            ];
        }
    }

    $fileName = 'Order-' . $order_number . '_format.csv';

    $csv = Writer::createFromString('');
    $csv->insertOne(array_keys($csvData[0])); // Insert headers
    $csv->insertAll($csvData); // Insert data rows

    // Set headers to force download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '";');

    // Output CSV data
    $csv->output();
    exit;
}

// public function formatUpload(Request $request)
//   {
//       $file = $request->file('csv_file');
//       $path = $file->getRealPath();
//       $rows = array_map('str_getcsv', file($path));
//       $dataRows = array_slice($rows, 1); // Remove header

//       if (count($dataRows) === 0) {
//           return back()->withErrors("CSV file is empty or invalid.");
//       }

//       $firstRow = $dataRows[0];
//       $orderId = $firstRow[5];

//       // Load the order with nested relationships
//       $order = Order::with([
//           'user',
//           'orderposting.OrderspostingDetails.orderspostingdetailsimis'
//       ])->find($orderId);

//       if (!$order || !$order->orderposting) {
//           return back()->withErrors("Order or posting not found.");
//       }

//       $orderPosting = $order->orderposting;
//       $detailsList = $orderPosting->OrderspostingDetails;
//       $detailsMap = $detailsList->keyBy('product_id');

//       // Collect already uploaded IMEIs
//       $existingImis = collect();
//       foreach ($detailsList as $detail) {
//           $existingImis = $existingImis
//               ->merge($detail->orderspostingdetailsimis->pluck('imi'))
//               ->merge($detail->orderspostingdetailsimis->pluck('imi2'));
//       }
//       $existingImis = $existingImis->unique()->toArray();

//       $stockSnos = collect($dataRows)->pluck(4)->toArray();
//       $stockItems = Stock::whereIn('sno', $stockSnos)
//           ->orWhereIn('imei', $stockSnos)
//           ->get()
//           ->keyBy('sno');

//       // Check IMEIs already sold
//       $soldImis = Orderspostingdetailsimi::whereIn('imi', $stockSnos)
//           ->orWhereIn('imi2', $stockSnos)
//           ->pluck('imi')
//           ->merge(
//               Orderspostingdetailsimi::whereIn('imi2', $stockSnos)->pluck('imi2')
//           )
//           ->unique()
//           ->toArray();

//       $userId = Auth::id();
//       $insertData = [];
//       $uploadCounter = [];

//       foreach ($dataRows as $row) {
//           $sno = $row[4];

//           if (empty($sno)) {
//         return back()->withErrors("There is no IMEI in the CSV file.");
//     }

//           if (in_array($sno, $soldImis)) {
//               return back()->withErrors("IMEI already sold: $sno");
//           }

//           $stock = $stockItems->get($sno);

//           if (!$stock) {
//               return back()->withErrors("IMEI/SNO $sno not found in stock.");
//           }

//           $productId = $stock->product_id;
//           $imei = $stock->imei;

//           if (!isset($detailsMap[$productId])) {
//               continue;
//           }

//           $detail = $detailsMap[$productId];
//           $uploadedCount = $uploadCounter[$productId] ?? $detail->orderspostingdetailsimis->count();
//           $uploadedCount++;

//           $uploadCounter[$productId] = $uploadedCount;

//           $insertData[] = [
//               'orderspostingdetails_id' => $detail->id,
//               'order_number' => $orderId,
//               'product_id' => $productId,
//               'imi' => $sno,
//               'imi2' => $imei,
//               'created_by' => $userId,
//           ];
//       }

//       if (!empty($insertData)) {
//           Orderspostingdetailsimi::insert($insertData);
//       }

//       // Final status update
//       $totalQuantity = $detailsList->sum('quantity');
//       $uploadedTotal = Orderspostingdetailsimi::whereIn('orderspostingdetails_id', $detailsList->pluck('id'))->count();

//       $newStatus = $uploadedTotal >= $totalQuantity ? 3 : 2;
//       $order->update(['status' => $newStatus]);
//       $orderPosting->update(['status' => $newStatus]);

//       return back()->with('success', 'IMEI uploaded successfully.');
//   }

public function formatUpload(Request $request)
{
    $file = $request->file('csv_file');
    $path = $file->getRealPath();
    $rows = array_map('str_getcsv', file($path));
    $dataRows = array_slice($rows, 1);

    $firstRow = $dataRows[0];
    $orderNumber = $firstRow[5];
    $csvPath = $file->store('upload\warehouse_uploads', 'local');

    $jobId = (string) Str::uuid();

    // Initialize job tracking
    $job = JobProgress::create([
        'user_id' => Auth::id(),
        'job_id' => $jobId,
        'type' => 'warehouse_upload',
        'order_number' => $orderNumber,
        'status' => 'queued',
        'message' => 'Job is queued for processing.',
    ]);
    $userId = Auth::id();

    // Dispatch with jobId (UUID)
    ImeiBulkUpload::dispatch($csvPath, $userId, $jobId)->onQueue('warehouse_upload');

    // Use the UUID in the message, not $job->id
    return redirect()->back()->with('success', "Stock upload queued (Job: JB-{$job->id}).");
}

//   public function refreshStock()
//   {
//     DB::table('stocks')->update([
//     'sno' => DB::raw('TRIM(sno)'),
//     'imei' => DB::raw('TRIM(imei)'),
// ]);

// return back()->with('success', 'Stock refreshed successfully.');
//   }

public function refreshStock()
{
    // First, trim any whitespace in sno and imei
    DB::table('stocks')->update([
        'sno' => DB::raw('TRIM(sno)'),
        'imei' => DB::raw('TRIM(imei)'),
    ]);

    // Then, delete duplicates — keep only the first occurrence of each sno
    DB::statement("
        DELETE s1 FROM stocks s1
        INNER JOIN stocks s2
        ON s1.sno = s2.sno
        AND s1.id > s2.id
    ");

    return back()->with('success', 'Stock refreshed successfully and duplicates removed.');
}


// public function formatUpload(Request $request)
//   {
//       $path = $request->file('csv_file')->getRealPath();
//       $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
//       $data = array_map('str_getcsv', file($path));
//       $csv_data = array_slice($data, 1, count($row_index));

//       $count = 0;

//       foreach ($csv_data as $key => $value) {
//         $distributor_id = $value[0];
//         $sno = $value[4];
//         $orderid = $value[5];
//         // dd($orderid);

//         $orderInfo = Order::with('user')->find($orderid);

//         if (!$orderInfo) {
//           return redirect()->back()->withErrors("Order number doesn't match.");
//         }

//         $orderStatus = $orderInfo->status;

//         if ($orderStatus !== 1 && $orderStatus !== 2) {
//           return redirect()->back()->withErrors("This order is not ready to add IMEI");
//         }

//         $officeId = $orderInfo->user->officeid;

//         if ($officeId !== $distributor_id) {
//           return redirect()->back()->withErrors("Distributor Doesn't match for this order.");
//         }

//         $stockInfo = Stock::where('sno', $sno)->orWhere('imei', $sno)->get();


//         if (!$stockInfo || $stockInfo->isEmpty()) {
//           return redirect()->back()->withErrors("One or more IMEI is not available in stock.");
//         }

//         $imei = $stockInfo->pluck('imei')->first();

//         $product_id = $stockInfo->pluck('product_id');

//         $orderPostingDetailsImeiInfo = Orderspostingdetailsimi::where('imi', $sno)->orWhere('imi2', $sno)->get();

//         if ($orderPostingDetailsImeiInfo->isNotEmpty()) {
//           return redirect()->back()->withErrors("One or more IMEI has been sold.");
//         }

//         $orderPostingInfo = Ordersposting::where('orader_number', $orderid)->first();

//         $orderPostingId = $orderPostingInfo->id;


//         $orderPostingDetailsInfo = Orderspostingdetail::with('orderspostingdetailsimis')->where('orderspostings_id', $orderPostingId);
//         $orderPostingDetailsList = $orderPostingDetailsInfo->get();

//         $productID = $orderPostingDetailsInfo->pluck('product_id');

//         foreach ($product_id as $id) {
//           if (!$productID->contains($id)) {
//             return redirect()->back()->withErrors("Product doesn't match for this order. Please check your CSV file.");
//           }
//         }

//         $totalQuantity = $orderPostingDetailsInfo->sum('quantity');
//         $countExisting = Orderspostingdetailsimi::whereIn('orderspostingdetails_id', $orderPostingDetailsList->pluck('id'))->count();
//         $countNew = 0;

//         $product_ids = $stockInfo->pluck('product_id')->toArray();
//         $user_id = Auth::id();


//         foreach ($product_ids as $product_id) {

//           $orderPostingDetails = $orderPostingDetailsList->where('product_id', $product_id)->first();

//           if ($orderPostingDetails) {
//             $allowedQuantity = $orderPostingDetails->quantity; // Quantity allowed for this product_id
//                 $uploadedCount = Orderspostingdetailsimi::where('orderspostingdetails_id', $orderPostingDetails->id)->count() + 1; // Adding current upload

//                 if ($uploadedCount > $allowedQuantity) {
//                     $productName = Product::where('id', $product_id)->value('model'); // Fetch product name
//                     return redirect()->back()->withErrors("Exceeded allowed quantity for product: $productName. Allowed: $allowedQuantity, Uploaded: $uploadedCount");
//                 }

//             $data1 = [
//               'orderspostingdetails_id' => $orderPostingDetails->id,
//               'order_number' => $orderid,
//               'product_id' => $product_id,
//               'imi' => $value[4],
//               'imi2' => $imei,
//               'created_by' => $user_id,
//             ];

//             Orderspostingdetailsimi::create($data1);
//             $countNew++;
//           }
//         }

//         $count++;

//         if ($count > 0) {
//           $orderid = $value[5];

//           $totalCount = $countExisting + $countNew;
//           // dd($totalCount);

//           if ($totalCount >= $totalQuantity) {
//             Order::find($orderid)->update(['status' => 3]);
//             Ordersposting::where('orader_number', $orderid)->update(['status' => 3]);
//           } else {
//             Order::find($orderid)->update(['status' => 2]);
//             Ordersposting::where('orader_number', $orderid)->update(['status' => 2]);
//           }
//         }
//       }



//     return redirect()->back()->with('success', 'Data has been inserted successfully');

//   }

  public function dataSink()
{
    DB::statement('
        DELETE p1
        FROM purchases p1
        JOIN purchases p2 ON p1.sno = p2.sno AND p1.id > p2.id
    ');

    DB::statement('
        DELETE p1
        FROM sales p1
        JOIN sales p2 ON p1.sno = p2.sno AND p1.id > p2.id
    ');

    DB::statement('
        DELETE p1
        FROM smsdetails p1
        JOIN smsdetails p2 ON p1.sno = p2.sno AND p1.id > p2.id
    ');
  return redirect()->back();
}


public function receiveAndDeliveryReport()
{
  return view('warehouse.receiveAndDeliveryReport');
}

public function currentMonthReceiveReport()
{

  $startDate = now()->startOfMonth();
  $endDate = now()->endOfMonth();


  $receivedStocks = Stock::whereBetween('created_at', [$startDate, $endDate])
    ->orderByDesc('created_at')
    ->take(200000)
    ->get();


  $receiveData = $receivedStocks->map(function ($stock) {
    return [
      'Product Name' => $stock->product->name ?? '-',
      'Product Model' => $stock->product->model ?? '-',
      'IMEI-1' => $stock->sno ?? '-',
      'IMEI-2' => $stock->imei ?? '-',
      'Receive Date' => $stock->created_at->format('Y-m-d') ?? '-',
    ];
  });

  $currentMonthName = now()->format('F');
  $fileName = "receive_report($currentMonthName).xlsx";

  return (new FastExcel($receiveData))->download($fileName);
}

public function allReceiveReport(Request $request)
{
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
  $receivedStocks = Stock::whereDate('created_at', '>=', $fdate)
    ->whereDate('created_at', '<=', $todate)
    ->orderByDesc('created_at')
    ->take(200000)
    ->get();

    $receiveData = $receivedStocks->map(function ($stock) {
        return [
            'Brand Name' => $stock->brand->name ?? '-',
            'Product Name' => $stock->product->name ?? '-',
            'Product Model' => $stock->product->model ?? '-',
            'IMEI-1' => $stock->sno ?? '-',
            'IMEI-2' => $stock->imei ?? '-',
            'Receive Date' => $stock->created_at->format('Y-m-d') ?? '-',
        ];
    });

    // Format the filename
    $filename = Carbon::parse($fdate)->format('jS M\'y') . '-' . Carbon::parse($todate)->format('jS M\'y') . ' Receive_report.xlsx';

    // Download the file with the custom filename
    return (new FastExcel($receiveData))->download($filename);

     // return (new FastExcel($receiveData))->download('receive_report.xlsx');
}



public function currentMonthDeliveryReport()
{

  $startDate = now()->startOfMonth();
  $endDate = now()->endOfMonth();


  $deliveryStocks = Purchase::whereBetween('created_at', [$startDate, $endDate])
    ->orderByDesc('created_at')
    ->take(200000)
    ->get();


  $deliveryData = $deliveryStocks->map(function ($delivered) {
    return [
      'Product Name' => $delivered->product->name ?? '-',
      'Product Model' => $delivered->product->model ?? '-',
      'Order Number' => $delivered->order_number ?? '-',
      'LD Name' => $delivered->user->firstname ?? '-',
      'LD Code' => $delivered->user->officeid ?? '-',
      'IMEI-1' => $delivered->sno ?? '-',
      'IMEI-2' => $delivered->imei ?? '-',
      'Delivery Date' => $delivered->created_at->format('Y-m-d') ?? '-',

    ];
  });
  // dd($deliveryData);

  $currentMonthName = now()->format('F');
  $fileName = "delivery_report($currentMonthName).xlsx";

  return (new FastExcel($deliveryData))->download($fileName);
}

public function allDeliveryReport(Request $request)
{
  $fdate = $request->input('fdate');
  $todate = $request->input('todate');

  $deliveryStocks = Purchase::whereBetween('created_at', [$fdate, $todate])
    ->orderByDesc('created_at')
    ->take(200000)
    ->get();


  $deliveryData = $deliveryStocks->map(function ($delivered) {
    return [
      'Product Name' => $delivered->product->name ?? '-',
      'Product Model' => $delivered->product->model ?? '-',
      'Order Number' => $delivered->order_number ?? '-',
      'LD Name' => $delivered->user->firstname ?? '-',
      'LD Code' => $delivered->user->officeid ?? '-',
      'IMEI-1' => $delivered->sno ?? '-',
      'IMEI-2' => $delivered->imei ?? '-',
      'Delivery Date' => $delivered->created_at->format('Y-m-d') ?? '-',

    ];
  });
  // dd($deliveryData);

   // Format the filename
   $filename = Carbon::parse($fdate)->format('jS M\'y') . '-' . Carbon::parse($todate)->format('jS M\'y') . ' Delivery_report.xlsx';

   // Download the file with the custom filename
   return (new FastExcel($deliveryData))->download($filename);
   // return (new FastExcel($deliveryData))->download('delivery_report.xlsx');
}







}
