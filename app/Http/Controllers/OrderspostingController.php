<?php

namespace App\Http\Controllers;

use App\Models\Ordersposting;
use Illuminate\Http\Request;
use PDF;
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
use App\Order;
use App\Orderdetail;

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
use App\Orderspostingdetail;
use Haruncpi\LaravelIdGenerator\IdGenerator;
class OrderspostingController extends Controller
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

    public function index(Request $request)
    {
        $queryarray = $request->query('search');
        $fdate = $request->input('fdate');
        $todate = $request->input('todate');

        $orderspostings = Ordersposting::orderBy('id', 'desc');

        if ($queryarray !== null) {
            $orderspostings->where('status', $queryarray);
        } else {
            $orderspostings->where('status', 0);
        }

        if ($fdate !== null && $todate == null) {
            $timestamp = strtotime($fdate);
            $orderspostings->where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
        } elseif ($fdate == null && $todate !== null) {
            $timestamp = strtotime($todate);
            $orderspostings->where('created_at', '<=', date('Y-m-d 23:59:59', $timestamp));
        } elseif ($fdate !== null && $todate !== null) {
            $timestampFdate = strtotime($fdate);
            $timestampTodate = strtotime($todate);
            $orderspostings->whereBetween('created_at', [date('Y-m-d 00:00:00', $timestampFdate), date('Y-m-d 23:59:59', $timestampTodate)]);
        }



        $orderspostings = $orderspostings->paginate(1000);

        return view('ordersposting.index', compact('orderspostings', 'queryarray', 'fdate','todate'))
            ->with('i', ($orderspostings->currentPage() - 1) * $orderspostings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ordersposting = new Ordersposting();
        return view('ordersposting.create', compact('ordersposting'));
    }


    public function store(Request $request)
    {

        $rules = [
            "orderspostings_id"    => "required|numeric|min:1",
            "model"    => "required|array|min:1",
            "model.*"  => "required|numeric|min:1",
            "quintity"    => "required|array|min:1",
            'quintity.*' => 'required|numeric|min:1',
         ];


         $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

       // dd($request->all());

        $model = $request->input('model');
        $quantity =$request->input('quintity');
        $orderspostings_id =$request->input('orderspostings_id');



        for ($count=0; $count < count($model); $count++) {
            $productList=Product::findOrFail($model[$count]);
            $insert[] = array(
               'orderspostings_id' =>$orderspostings_id,
               'product_id'     => $model[$count],
               'quantity'     => $quantity[$count],
               'quantity_acc'     =>0,
               'price'     => $productList->dp,
               'price_acc'     => 0,
           );
          }
         //dd($insert);

        $Orderdetail = Orderspostingdetail::insert($insert);

        if($Orderdetail){
            return response()->json(['message' => 'Post created successfully']);
        }else{

            return response()->json(['errors' => $Orderdetail]);
        }
        // return redirect()->back();
        // // ->withErrors($validator) // Send validation errors to the view
        // // ->withInput();
    }


    public function show($id)
    {
        $ordersposting = Ordersposting::find($id);
        return view('ordersposting.show', compact('ordersposting'));
    }
    public function postinginvoice($id)
    {
        // $ordersposting = Ordersposting::find($id);
        // return view('ordersposting.show', compact('ordersposting'));

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
        return $pdf->stream('Invoice_'. '.pdf', 'I');
    }

    public function ShowOrader($id)
    {

        $orader = Order::findOrFail($id);
        $postings = Ordersposting::where('orader_number', $id)
            ->whereNotNull('approve_by')
            ->get();

        $productList = Product::pluck('id', 'model');
        $oraderdetails = Orderdetail::where('orader_number', $id)->get();



        return view('accounts.show', ['orader' => $orader, 'oraderdetails' => $oraderdetails, 'productList' => $productList, 'postings' => $postings]);
    }


    public function edit($id)
    {
        $orderspostings_id = $id;
        $ordersposting = Ordersposting::find($id);
        $orderNumber = $ordersposting->orader_number;
        $orderInfo = Order::find($orderNumber);
        $distributorId = $orderInfo->upazila_id;
        $distributor = User::find($distributorId);
        $productList = Product::select('id', 'name', 'model')->get();
        $upazilaResult = Tsoupazila::select('id', 'name', 'bn_name', 'upazila_id')->where(['user_id' => Auth::id()])->orderBy('id', 'desc')->get();
        $upazilas = $upazilaResult->toArray();
        return view('ordersposting.edit', compact('ordersposting', 'productList', 'upazilas', 'orderspostings_id','distributor'));
    }


    public function delete($id, Request $request)
    {
        $cancel_reason = $request->input('cancel_reason');

        // dd($cancel_reason);
        $orderPostingInfo = Ordersposting::find($id);
        // dd($orderPostingInfo);
        $orderNumber = $orderPostingInfo->orader_number;
        // dd($orderNumber);
        $orderInfo = Order::find($orderNumber);
        // dd($orderInfo);
        $orderPostingInfo->update(['status' => 7, 'remarks' => $cancel_reason]);
        $orderInfo->update(['status' => 7]);
        return redirect()->back()
            ->with('success', 'Order rejected successfully');


    }


    public function reverse($id)
    {
        $orderPostingInfo = Ordersposting::find($id);
        // dd($orderPostingInfo);
        $orderNumber = $orderPostingInfo->orader_number;
        // dd($orderNumber);
        $orderInfo = Order::find($orderNumber);
        // dd($orderInfo);
        $orderPostingInfo->update(['status' => 0, 'remarks' => null]);
        $orderInfo->update(['status' => 1]);
        return redirect()->back()
            ->with('success', 'Order status reversed successfully');


    }


public function update(Request $request, Ordersposting $ordersposting)
    {

        $rules = [
            "orderspostings_id"    => "required|numeric|min:1",
            "product"    => "required|array|min:1",
            "product.*"  => "required|distinct|numeric|min:1",
            "quintity"    => "required|array|min:1",
            'quintity.*' => 'required|numeric|min:1',
            "price"    => "required|array|min:1",
            'price.*' => 'required|numeric|min:1',

         ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator) // Send validation errors to the view
                    ->withInput(); // Keep the old input data
            }

        $orderspostings_id = $request->input('orderspostings_id');
        $deleteList = $request->input('id');
        $product_id = $request->input('product');
        $quantity = $request->input('quintity');
        $price = $request->input('price');
        $price_acc = $request->input('price_acc');
        $orader_number = $request->input('orader_number');
        $remarks = $request->input('remarks');

        $Ordersposting = Ordersposting::where('id', $orderspostings_id)->update(['approve_by' => ($currentuserid = Auth::user()->id), 'status' => 1,'remarks'=>$remarks]);
        $order = Order:: where('id', $orader_number)->update(['status' => 2]);
        $alldestroy = Orderspostingdetail::destroy($deleteList);

        if (!empty($product_id)) {
            for ($count = 0; $count < count($product_id); $count++) {
                $insert[] = [
                    'orderspostings_id' => $orderspostings_id,
                    'product_id' => $product_id[$count],
                    'quantity' => $quantity[$count],
                    'price' => $price[$count],
                    'price_acc' => $price_acc[$count],
                ];
            }
            $orderspostingdetail = Orderspostingdetail::insert($insert);
        }
        return redirect()
            ->route('orderspostings.index')
            ->with('success', 'Order Send to warehouse');
    }


    public function destroy($id)
    {
        $ordersposting = Ordersposting::find($id)->delete();

        return redirect()
            ->route('orderspostings.index')
            ->with('success', 'Ordersposting deleted successfully');
    }

    public function orderComparison(Request $request)
    {
        $fdate = $request->input('fdate');
        $todate = $request->input('todate');

        $today = date('Y-m-d');

        $ordersQuery = Order::with(['details', 'orderposting.OrderspostingDetails'])->orderBy('id', 'desc');

        if ($fdate !== null && $todate == null) {
            $timestamp = strtotime($fdate);
            $ordersQuery->where('created_at', '>=', date('Y-m-d 00:00:00', $timestamp));
        } elseif ($fdate == null && $todate !== null) {
            $timestamp = strtotime($todate);
            $ordersQuery->where('created_at', '<=', date('Y-m-d 23:59:59', $timestamp));
        } elseif ($fdate !== null && $todate !== null) {
            $timestampFdate = strtotime($fdate);
            $timestampTodate = strtotime($todate);
            $ordersQuery->whereBetween('created_at', [date('Y-m-d 00:00:00', $timestampFdate), date('Y-m-d 23:59:59', $timestampTodate)]);
        }else {
            $ordersQuery->whereDate('created_at', $today);
        }

        $report = $ordersQuery->get()->map(function ($order) {

            $detailsQty = $order->details->sum('quantity');

            $postingQty = optional($order->orderposting)
                ->OrderspostingDetails
                ? $order->orderposting->OrderspostingDetails->sum('quantity')
                : 0;

            return [
                'order_id'     => $order->id,
                'details_qty'  => $detailsQty,
                'posting_qty'  => $postingQty,
                'difference'   => $detailsQty - $postingQty,
                'status'       => $detailsQty == $postingQty ? 'Matched' : 'Mismatch',
            ];
        });

        return view('reports.accounts.order_comparison', compact('report', 'fdate', 'todate'));
    }



}
