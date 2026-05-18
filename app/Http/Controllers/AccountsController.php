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
use App\Models\Ordersposting;
use Rap2hpoutre\FastExcel\FastExcel;

use App\Retailer;
use App\Brand;
use App\Cat;
use App\Product;
use App\Order;
use App\Orderspostingdetail;
use App\Specification;
use App\Stock;

use App\Purchase;
use App\Sale;
use App\Preturn;


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

class AccountsController extends Controller
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
    if (Auth::user()->level != 7) { return redirect()->route('logout');}
  }

  public function DashboardView(){
    if (Auth::user()->level != 7) { return redirect()->route('logout');}

    //return "Hello accounts Controller";

    $posting = Ordersposting::all();
  
    $_SESSION['favicon'] = self::$favicon;
    $_SESSION['logo'] = self::$logo;

    //return redirect(route('accounts.wcheckProduct')); 
    return view('accounts.dashboard')->with(['posting'=> $posting]);
    
  }

  public function Test(){
    if (Auth::user()->level != 7) { return redirect()->route('logout');}
    return redirect(route('accounts.wcheckProduct')); 
    return view('accounts.dashboard');
  }



//================WcheckProduct=======================


  public function WcheckProductView(){
    if (Auth::user()->level != 7) { return redirect()->route('logout');}
    
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

    return view('accounts.wcheckProduct',['ssdata'=>$ssdata,'wcheckProducts'=>$data,'dataCount'=>$dataCount]);

  }


  public function WcheckProductViewStore(Request $request){
    if (Auth::user()->level != 7) { return redirect()->route('logout');}

    Session::forget(['imei']);

    $this->validate($request, [
      'imei' => 'required'
    ]);


    //dd($request->all());

    $imei = $request->get('imei');
    
    Session::put(['imei'=>$imei]);

  return redirect(route('accounts.wcheckProduct'));
  


  }



  public function WcheckProductReplace(Request $request){
    if (Auth::user()->level != 7) { return redirect()->route('logout');}


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





public function DailyStockReportView(){
  if (Auth::user()->level != 7) { return redirect()->route('logout');}
  //$userCount = User::count();
  
//--------------------------------------------------
  $productResult = Product::select('id','name','model')->orderBy('id','desc')->get();
  $products = $productResult->toArray();


$product_ids = Session::get('product_id');



$check = Product::where('id',$product_ids)->count();

if ($check > 0 ) {


  $productResult = Product::select('id', 'name', 'model')
  ->where('id', $product_ids)
  ->orderBy('id', 'desc')
  ->get();

  $product = $productResult[0];

// $product_id = $product1['id'];
$product_id = $product->id;
 $name = $product->name;
  $model = $product->model;



$dailyStockReports=[];

// with distributor_id========
$pcount = Stock::where('product_id',$product_id)->count();


if ($pcount > 0 ) {
$PurchaseResult = Stock::select(DB::raw('SUM(quantity) AS sin'))->where('product_id',$product_id)->groupBy('product_id')->first();
$Purchases = $PurchaseResult->toArray();

$sin = $Purchases['sin'];
} else {

$sin = 0;
}

$scount = Purchase::where('product_id',$product_id)->count();

if ($scount > 0 ) {
$SaleResult = Purchase::select(DB::raw('COUNT(product_id) as sout'))->where('product_id',$product_id)->groupBy('product_id')->first();
$Sales = $SaleResult->toArray();

$sout = $Sales['sout'];
} else {

$sout = 0;



}


$dailyStockReports[] = [
  // 'product_id' => $product_id,
  'product' => $name,
  'model' => $model,
  'stockin' => $sin,
  'stockout' => $sout,
  'stock' => $sin - $sout
]; 

}

else{
  $dailyStockReports[] = [
    // 'product_id' => $product_id,
    'product' => "-",
    'model' => "-",
    'stockin' => "-",
    'stockout' => "-",
    'stock' => "-"
  ]; 


}
// dd($dailyStockReports);
// //Session::forget(['user_id','fdate','todate']);
return view('accounts.dailyStockReport',['dailyStockReports'=>$dailyStockReports, 'products'=>$products]);







}


public function DailyStockReportViewStore(Request $request){
  if (Auth::user()->level != 7) { return redirect()->route('logout');}

  //dd($request->all());

  Session::forget(['product_id']);


  
  $this->validate($request, [
    'product_id' => 'required',
 
  ]);



  $product_id = $request->get('product_id');


  Session::put(['product_id'=>$product_id]);


return redirect(route('accounts.dailyStockReport'));


}

public function vatReport()
  {
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

    $orders = null;
    $orderDetails = [];

    if ($fdate == null && $todate == null) {
      $orders = null;
    } elseif ($fdate !== null && $todate !== null) {
      $orders = Order::where('status', 5)
        ->whereBetween(DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), [$fdate, $todate])
        ->with('user')
        ->get();
    }else{
      $orders = null;
    }


    if ($orders) {
      $orderPosings = Ordersposting::whereIn('orader_number', $orders->pluck('id'))->get();

      foreach ($orderPosings as $orderPosing) {
        $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosing->id)->with('products')->get();

        foreach ($orderPostingDetailInfo as $detailInfo) {
          $orderDetails[] = [
            'postingID' => $detailInfo->orderspostings_id,
            'productCode' => $detailInfo->products->product_code,
            'productName' => $detailInfo->products->name,
            'productModel' => $detailInfo->products->model,
            'chalan_type' =>$detailInfo->products->chalan_type ?? '',
            'quantity' => $detailInfo->quantity,
            'customerCode' => $orders->where('id', $orderPosing->orader_number)->pluck('user.officeid')->first(),
            'invoiceDate' => $orders->where('id', $orderPosing->orader_number)->pluck('updated_at')->first(),
            'issueDate' => $orders->where('id', $orderPosing->orader_number)->pluck('created_at')->map(function ($date) {
              return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('m/d/Y');
            })->first(),
            'issueTime' => $orders->where('id', $orderPosing->orader_number)->pluck('created_at')->map(function ($time) {
              return \DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('H:m:s');
            })->first(),
            'deliveryDate' => $orders->where('id', $orderPosing->orader_number)->pluck('updated_at')->map(function ($date) {
              return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('m/d/Y');
            })->first(),
            'deliveryTime' => $orders->where('id', $orderPosing->orader_number)->pluck('updated_at')->map(function ($time) {
              return \DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('H:m:s');
            })->first(),
            'address' => $orders->where('id', $orderPosing->orader_number)->pluck('user.address')->first(),
            'deliveryInfo' => $orderPosing->delivery_info,


          ];
        }
      }
    }

    return view('accounts.vatReport', compact('orderDetails'));
  }



  public function vatReportStore(Request $request)
  {
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    
    Session::put('fdate', $fdate);
    Session::put('todate', $todate);

    return redirect(route('accounts.vatReport'));
  }


  public function distributorDeliveryReport()
  {
    $distributorDeliveryReport = [];
    $orders = [];

    $distributorResult = User::where('level', 100)->get();
    $distributors = $distributorResult->toArray();

    $distributor_id = Session::get('distributor_id');
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

    if ( $distributor_id == 'All') {
      if ($fdate == null & $todate == null) {
        $orders = [];
      } elseif ($fdate != null && $todate != null) {
        $orders = Order::where('status', 5)
          ->whereBetween(DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), [$fdate, $todate])
          ->with('user')
          ->whereHas('user', function ($query) use ($fdate, $todate) {
            $query->where('level', 100);
          })
          ->get();
      } else {
        $orders = [];
      }
    } else {
      if ($fdate == null & $todate == null) {
       $orders = [];
      } elseif ($fdate != null && $todate != null) {
        $orders = Order::where('status', 5)
          ->whereBetween(DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), [$fdate, $todate])
          ->with('user')
          ->whereHas('user', function ($query) use ($fdate, $todate, $distributor_id) {
            $query->where('level', 100)->where('id', $distributor_id);
          })
          ->get();
      } else {
        $orders = [];
      }
    }

    if ($orders) {
      $orderPosings = Ordersposting::whereIn('orader_number', $orders->pluck('id'))->get();

      foreach ($orderPosings as $orderPosing) {
        $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosing->id)->with('products')->get();

        foreach ($orderPostingDetailInfo as $detailInfo) {
          $orderDetails[] = [
            'postingID' => $detailInfo->orderspostings_id,
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
    }
    else{
      $orderDetails=[];
    }


    return view('accounts.distributorDeliveryReport', compact('orderDetails', 'distributors'));
  }


  public function distributorDeliveryReportStore(Request $request)
  {
    $distributor_id = $request->input('distributor_id');
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    Session::put(['distributor_id' => $distributor_id, 'fdate' => $fdate, 'todate' => $todate]);

    return redirect()->route('accounts.distributorDeliveryReport');
  }

  // public function deliveryReport()
  // {
  //   $deliveryReport = [];
  //   $orders = [];

  //   $distributorResult = User::where('level', 100)->get();
  //   $distributors = $distributorResult->toArray();

  //   $distributor_id = Session::get('distributor_id');
  //   $fdate = Session::get('fdate');
  //   $todate = Session::get('todate');

  //   if ($distributor_id == 'All') {
  //     if ($fdate == null & $todate == null) {
  //       $orders = [];
  //     } elseif ($fdate != null && $todate != null) {
  //       $orders = Order::where('status', 5)
  //         ->whereBetween(DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), [$fdate, $todate])
  //         ->with('user')
  //         ->whereHas('user', function ($query) use ($fdate, $todate) {
  //           $query->where('level', 100);
  //         })
  //         ->get();
  //     } else {
  //       $orders = [];
  //     }
  //   } else {
  //     if ($fdate == null & $todate == null) {
  //       $orders = [];
  //     } elseif ($fdate != null && $todate != null) {
  //       $orders = Order::where('status', 5)
  //         ->whereBetween(DB::raw("DATE_FORMAT(updated_at, '%Y-%m-%d')"), [$fdate, $todate])
  //         ->with('user')
  //         ->whereHas('user', function ($query) use ($fdate, $todate, $distributor_id) {
  //           $query->where('level', 100)->where('id', $distributor_id);
  //         })
  //         ->get();
  //     } else {
  //       $orders = [];
  //     }
  //   }
  //   if ($orders) {
  //     $orderIDs = $orders->pluck('id');
  //     $orderDistributorName = $orders->pluck('user.firstname');

  //     $orderDate = $orders->pluck('updated_at')->transform(function ($date) {
  //       return \Carbon\Carbon::parse($date)->format('Y-m-d');
  //     });

  //     $orderPosings = Ordersposting::whereIn('orader_number', $orderIDs)->get();
  //     $orderPostingID = $orderPosings->pluck('id');
  //     $orderPostingDetailInfo = Orderspostingdetail::whereIn('orderspostings_id', $orderPostingID)->with('products')->get();

  //     $orderDetails = [];
  //     foreach ($orderPostingID as $id) {
  //       $details = $orderPostingDetailInfo->where('orderspostings_id', $id);
  //       $orderDetails[$id] = [
  //         'productQuantity' => $details->pluck('quantity'),
  //         'productPrice' => $details->pluck('price'),
  //       ];
  //     }

  //     $deliveryReport = [
  //       'orderID' => $orderIDs,
  //       'distributorCode' => $orderDistributorName,
  //       'orderPostingID' => $orderPostingID,
  //       'orderDetails' => $orderDetails,
  //       'orderDate' => $orderDate,
  //     ];
  //   }else{
  //     $orderDetails=[];
  //   }

  //   return view('accounts.deliveryReport', compact('deliveryReport', 'distributors'));
  // }

  public function deliveryReport()
{
    $deliveryReport = [];
    $orders = [];
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

    if ($fdate != null && $todate != null) {
        $orders = Purchase::with('user')
            ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$fdate, $todate])
            ->orderBy('order_number', 'asc')
            ->get();
    }

    if (!empty($orders)) {
        $orderNumbers = collect($orders)->pluck('order_number')->unique();
        $distributorNames = [];
        $orderDates = [];
        $quantities = [];

        foreach ($orderNumbers as $orderNumber) {
            $order = collect($orders)->where('order_number', $orderNumber)->first();
            $user = $order->user;
            if ($user) {
                $distributorNames[$orderNumber] = $user->firstname;
                $orderDates[$orderNumber] = $order->created_at->toDateString();
                $quantities[$orderNumber] = collect($orders)->where('order_number', $orderNumber)->count();
            }
        }

        $deliveryReport = [
            'orderNumber' => $orderNumbers,
            'distributorName' => $distributorNames,
            'orderDate' => $orderDates,
            'quantity' => $quantities,
        ];
    }

    return view('accounts.deliveryReport', compact('deliveryReport'));
}

  public function deliveryReportStore(Request $request)
  {
    $distributor_id = $request->input('distributor_id');
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    Session::put(['distributor_id' => $distributor_id, 'fdate' => $fdate, 'todate' => $todate]);


    return redirect()->route('accounts.deliveryReport');
  }

  public function stockReceiveReport()
  {
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');

   $stockDeliveryReport = [
      'productModel' => [], // Initialize productModel as an empty array
    ];

    $stocks = collect([]);


    if ($fdate == null && $todate == null) {
      $stocks = collect([]);
    } elseif ($fdate !== null && $todate === null) {
      $timestamp = strtotime($fdate);

      $stocks = Stock::with('product')
        ->when($fdate, function ($query) use ($fdate, $timestamp) {
          return $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
        })
        ->get();
    } elseif ($fdate == null && $todate !== null) {
      $timestamp = strtotime($todate);

      $stocks = Stock::with('product')
        ->when($todate, function ($query) use ($todate, $timestamp) {
          return $query->where('created_at', '<=', date('Y-m-d 23:59:59', $timestamp));
        })
        ->get();
    } elseif ($fdate !== null && $todate !== null) {
      $timestampFdate = strtotime($fdate);
      $timestampTodate = strtotime($todate);

      $stocks = Stock::with('product')
        ->when($fdate && $todate, function ($query) use ($timestampFdate, $timestampTodate) {
          if ($timestampFdate == $timestampTodate) {

            $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestampFdate))
              ->where('created_at', '<=', date('Y-m-d 23:59:59', $timestampTodate));
          } else {

            $query->whereBetween('created_at', [
              date('Y-m-d 00:00:00', $timestampFdate),
              date('Y-m-d 23:59:59', $timestampTodate)
            ]);
          }
        })
        ->get();
    }


    if ($stocks) {
      $productDetailsWithCounts = $stocks->pluck('product')->groupBy('model')->map(function ($group) {
          $firstProduct = $group->first();
  
          return [
              'count' => count($group),
              'name' => optional($firstProduct)->name,
              'color' => optional($firstProduct)->color ?? '-',
          ];
      });


      $productModel = $productDetailsWithCounts->keys()->toArray();

      $stockReceiveReport = [
        'productModel' => $productModel,
        'productDetailsWithCounts' => $productDetailsWithCounts,
      ];
    }

    return view('accounts.stockReceiveReport', compact('stockReceiveReport'));
  }



  public function stockReceiveReportStore(Request $request)
  {
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');

    Session::put('fdate', $fdate);
    Session::put('todate', $todate);

    return redirect(route('accounts.stockReceiveReport'));
  }


  public function stockDeliveryReport()
{
    $fdate = Session::get('fdate');
    $todate = Session::get('todate');
    $stockDeliveryReport = [
        'productModel' => [], // Initialize productModel as an empty array
    ];

    $deliveredStocks = collect([]);

    if ($fdate == null && $todate == null) {
        $deliveredStocks = collect([]);
    } elseif ($fdate !== null && $todate === null) {
        $timestamp = strtotime($fdate);

        $deliveredStocks = Purchase::with('product')
            ->when($fdate, function ($query) use ($fdate, $timestamp) {
                return $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
            })
            ->get();
    } elseif ($fdate == null && $todate !== null) {
        $timestamp = strtotime($todate);

        $deliveredStocks = Purchase::with('product')
            ->when($todate, function ($query) use ($todate, $timestamp) {
                return $query->where('created_at', '<=', date('Y-m-d 23:59:59', $timestamp));
            })
            ->get();
    } elseif ($fdate !== null && $todate !== null) {
        $timestampFdate = strtotime($fdate);
        $timestampTodate = strtotime($todate);

        $deliveredStocks = Purchase::with('product')
            ->when($fdate && $todate, function ($query) use ($timestampFdate, $timestampTodate) {
                return $query->when($timestampFdate == $timestampTodate, function ($query) use ($timestampFdate, $timestampTodate) {
                    return $query->where('created_at', '>=', date('Y-m-d 00:00:00', $timestampFdate))
                        ->where('created_at', '<=', date('Y-m-d 23:59:59', $timestampTodate));
                }, function ($query) use ($timestampFdate, $timestampTodate) {
                    return $query->whereBetween('created_at', [
                        date('Y-m-d 00:00:00', $timestampFdate),
                        date('Y-m-d 23:59:59', $timestampTodate)
                    ]);
                });
            })
            ->get();
    }

    if ($deliveredStocks->isNotEmpty()) {
        $productDetailsWithCounts = $deliveredStocks->pluck('product')->groupBy('model')->map(function ($group) {
            return [
                'count' => count($group),
                'name' => $group->first()->name,
                'color' => $group->first()->color,
            ];
        });

        $productModel = $productDetailsWithCounts->keys()->toArray();
        $stockDeliveryReport['productModel'] = $productModel;
        $stockDeliveryReport['productDetailsWithCounts'] = $productDetailsWithCounts;
    }

    return view('accounts.stockDeliveryReport', compact('stockDeliveryReport'));
}




  public function stockDeliveryReportStore(Request $request)
  {
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');

    Session::put('fdate', $fdate);
    Session::put('todate', $todate);

    return redirect(route('accounts.stockDeliveryReport'));
  }


  public function currentStockReport()
  {
    $todate = Session::get('todate');
    $currentStockReport = [];

    if ($todate !== null) {
      $timestamp = strtotime($todate);

      $currentStocks = Stock::with('product')->where('details', NULL)
        ->when($todate, function ($query) use ($todate, $timestamp) {
          return $query->where('created_at', '<=', date('Y-m-d 00:00:00', $timestamp));
        })
        ->get();
    } else {
      $currentStocks= collect([]);
    }

    $productDetailsWithCounts = $currentStocks->pluck('product')->groupBy('model')->map(function ($group) {
      $firstProduct = $group->first();
  
      return [
          'count' => count($group),
          'name' => $firstProduct ? $firstProduct->name : null,
          'color' => $firstProduct ? $firstProduct->color : null,
      ];
  });
  

    $productModel = $productDetailsWithCounts->keys()->toArray();

    $currentStockReport = [
      'productModel' => $productModel,
      'productDetailsWithCounts' => $productDetailsWithCounts,
    ];

    return view('accounts.currentStockReport', compact('currentStockReport'));
  }

  public function currentStockReportStore(Request $request)
  {
    $todate = $request->input('todate');

    Session::put('todate', $todate);

    return redirect(route('accounts.currentStockReport'));
  }


  public function closeReport()
  {


        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        

        if ($fdate !== null && $todate == null) {
            $timestamp = strtotime($fdate);
            $orderspostings= Purchase::where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
        } elseif ($fdate == null && $todate !== null) {
            $timestamp = strtotime($todate);
            $orderspostings= Purchase::where('created_at', '<=', date('Y-m-d 00:00:00', $timestamp));
        } elseif ($fdate !== null && $todate !== null) {
            $timestampFdate = strtotime($fdate);
            $timestampTodate = strtotime($todate);
            $orderspostings= Purchase::whereBetween('created_at', [date('Y-m-d 00:00:00', $timestampFdate), date('Y-m-d 23:59:59', $timestampTodate)]);
        }elseif($fdate == null && $todate == null){
          $orderspostings = Purchase::whereNull('id');
        }
      

       $orderspostings = $orderspostings->select('order_number', \DB::raw('SUM(quantity) as total_quantity'))
                                      ->groupBy('order_number')
                                      ->paginate(1000);

      // dd($orderspostings);

      return view('accounts.colseReport', compact('orderspostings'))
          ->with('i', ($orderspostings->currentPage() - 1) * $orderspostings->perPage());
  }



  public function closeReportStore(Request $request)
  {
    $fdate = $request->input('fdate');
    $todate = $request->input('todate');
    
    Session::put('fdate', $fdate);
    Session::put('todate', $todate);

    return redirect(route('accounts.closeReport'));
  }

   public function distributorDeliveryExcel()
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


  public function deliveryExcel()
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

public function vatDownload()
{
    $orders = Order::where('status', 5)->get();

    $deliveryReport = [];

    foreach ($orders as $order) {
        $orderPosings = Ordersposting::where('orader_number', $order->id)->first();
        $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosings->id)->with('products')->get();

        foreach ($orderPostingDetailInfo as $detail) {
            $deliveryReport[] = [
                'CompanyCode' => 'SXN01',
                'BranchCode' => '0002',
                'InvoiceNo' => $orderPosings->id . \Carbon\Carbon::parse($order->updated_at)->format('dmY'),

                'CustomerCode' => $order->user->officeid ?? '',
                'IssueDate' => $order->created_at->format('Y-m-d'),
                'IssueTime' => $order->created_at->format('H:i:s'),
                'DeliveryDate' => $order->updated_at->format('Y-m-d'),
                'DeliveryTime' => $order->updated_at->format('H:i:s'),
                'Place' => $order->user->address ?? '',
                'Car' => $orderPosings->delivery_info ?? '',
                'Remarks' => $orderPosings->remarks ?? '',
                'ChallanType' => $detail->products->chalan_type ?? '',
                'DistChanel' => '',
                'ErrorMessage' => '',
                'ProductCode' => $detail->products->product_code,
                'IssueQty' => $detail->quantity,
                'UnitTP' => '',
                'TotalWithoutSD' => '',
                'TotalSD' => '',
                'TotalWithoutVat' => '',
                'TotalVat' => '',
                'TotalWithVat' => '',
                'NetAmount' => '',
                'Discount' => '',
                'ErrorMessage' => '',

            ];
        }
    }

    return (new FastExcel($deliveryReport))->download('vatReport.xlsx');
}


public function currentMonthVatDownload()
{
  $currentMonth = now()->month;

  $orders = Order::where('status', 5)->whereMonth('updated_at', $currentMonth)->get();

  
  $monthName = now()->format('F');
  $year = now()->format('y');


    $deliveryReport = [];

    foreach ($orders as $order) {
        $orderPosings = Ordersposting::where('orader_number', $order->id)->first();
        $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosings->id)->with('products')->get();

        foreach ($orderPostingDetailInfo as $detail) {
            $deliveryReport[] = [
                'CompanyCode' => 'SXN01',
                'BranchCode' => '0002',
                'InvoiceNo' => $orderPosings->id . \Carbon\Carbon::parse($order->updated_at)->format('dmY'),
                'CustomerCode' => $order->user->officeid ?? '',
                'IssueDate' => $order->created_at->format('Y-m-d'),
                'IssueTime' => $order->created_at->format('H:i:s'),
                'DeliveryDate' => $order->updated_at->format('Y-m-d'),
                'DeliveryTime' => $order->updated_at->format('H:i:s'),
                'Place' => $order->user->address ?? '',
                'Car' => $orderPosings->delivery_info ?? '',
                'Remarks' => $orderPosings->remarks ?? '',
                'ChallanType' => $detail->products->chalan_type ?? '',
                'DistChanel' => '',
                'ErrorMessage' => '',
                'ProductCode' => $detail->products->product_code,
                'IssueQty' => $detail->quantity,
                'UnitTP' => '',
                'TotalWithoutSD' => '',
                'TotalSD' => '',
                'TotalWithoutVat' => '',
                'TotalVat' => '',
                'TotalWithVat' => '',
                'NetAmount' => '',
                'Discount' => '',
                'ErrorMessage' => '',

            ];
        }
    }

    $fileName = $monthName . "'" . $year . '-VatReport.xlsx';

    return (new FastExcel($deliveryReport))->download($fileName);
}

public function searchOrder($id)
{
  $orderspostings = Ordersposting::where('orader_number', $id);
  $queryarray = $orderspostings->pluck('status')->toArray();
  // dd($queryarray);
  $fdate= Null;
  $todate= Null;
  

  $orderspostings = $orderspostings->paginate(1000);

  return view('ordersposting.index', compact('orderspostings', 'queryarray', 'fdate','todate'))
      ->with('i', ($orderspostings->currentPage() - 1) * $orderspostings->perPage());
}

public function pendingReport()
    {
        $todaysReport = [];
        $orders = Order::where('status', 1)->get();
        //dd($orders);
        $orderPosings = Ordersposting::whereIn('orader_number', $orders->pluck('id'))->get();

        foreach ($orderPosings as $orderPosing) {
            $orderPostingDetailInfo = Orderspostingdetail::where('orderspostings_id', $orderPosing->id)->with('products')->get();

            foreach ($orderPostingDetailInfo as $detailInfo) {
                $todaysReport[] = [
                    'orderNumber' => $orderPosing->orader_number,
                    'customerName' => $orderPosing->Order->users->firstname ?? '',
                    'productModel' => $detailInfo->products->model ?? '',
                    'quantity' => $detailInfo->quantity ?? '',
                    'status' => $orderPosing->Order->status ?? '',
                ];
            }
        }

        // dd($todaysReport);

        return view('accounts.pendingReport', compact('todaysReport'));
    }









}
