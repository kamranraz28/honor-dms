<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
use App\Models\Ordersposting;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class DistributorOrderController extends Controller
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

    public function DashView(Request $request)
    {
        $queryarray = $request->query('search');

        if ($queryarray) {
            $oraderList = Order::where('upazila_id', Auth::id())->where('status', $queryarray);
        } else {
            $oraderList = Order::where('upazila_id', Auth::id())->where('status', 0);
        }

        $oraderList = $oraderList->paginate(500);

        $productList = Product::select('id', 'model', 'name', 'color')->get();
        $upazilaResult = User::select('id', 'firstname', 'officeid')
            ->where(['id' => Auth::id()])
            ->orderBy('id', 'desc')
            ->get();

        $upazilas = $upazilaResult->toArray();

        return view('distributor.order.list', [
            'upazilas' => $upazilas,
            'queryarray' => $queryarray,
            'productList' => $productList,
            'oraderList' => $oraderList,
        ])->with('i', ($oraderList->currentPage() - 1) * $oraderList->perPage());
    }

    public function Create()
    {

        $productList = Product::select('id', 'model', 'name', 'dp')->get();

        // dd($productList);
        return view('distributor.order.create', compact('productList'));
    }

    public function Store(Request $request)
    {
        $rules = [

            'quintity' => 'required|array|min:1',
            'quintity.*' => 'required|numeric|min:1',
            'model' => 'required|array|min:1',
            'model.*' => 'required|numeric|distinct|min:1',
        ];
        $validator = Validator::make($request->all(), $rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'data' => $request->all()], 422);
        }
        $model = $request->input('model');
        $quantity = $request->input('quintity');
        $price = $request->input('unitprice');
        $orders = new Order();
        $orders->upazila_id = $request->input('user_id');
        $orders->user_id = $request->input('user_id');
        $orders->remarks = $request->input('remarks');
        $orders->save();

        for ($count = 0; $count < count($model); $count++) {
            $productList = Product::findOrFail($model[$count]);
            //dd( $productList);
            $insert[] = [
                'orader_number' => $orders->id,
                'product_id' => $model[$count],
                'discount' => 0,
                'price' => $productList->dp,
                'quantity' => $quantity[$count],
                'quantity_acc' => 0,
            ];
        }

        $Orderdetail = Orderdetail::insert($insert);
        if ($Orderdetail) {
            return response()->json(['message' => 'Successfully created', 'url' => route('distributor.details', $orders->id)], 200);
        } else {
            App::abort(500, 'Error');
        }
    }

    public function ShowOrder($id)
    {
        $orader = Order::findOrFail($id);
        // dd( $orader );
        $postings = Ordersposting::where('orader_number', $id)
            ->whereNotNull('approve_by')
            ->get();

        // dd($postings);

        $productList = Product::pluck('id', 'model');
        $oraderdetails = Orderdetail::where('orader_number', $id)->get();
        // dd($productList);
        return view('distributor.order.show', ['orader' => $orader, 'oraderdetails' => $oraderdetails, 'productList' => $productList, 'postings' => $postings]);
    }

    public function Update(Request $request) {}


    public function printOrder($id)
    {

        $orader = Order::findOrFail($id);
        $postings = Ordersposting::where('orader_number', $id)
            ->whereNotNull('approve_by')
            ->get();

        $productList = Product::pluck('id', 'model');
        $oraderdetails = Orderdetail::where('orader_number', $id)->get();
        // return view('tso.orader.show', ['orader' => $orader, 'oraderdetails' => $oraderdetails, 'productList' => $productList, 'postings' => $postings]);

        //return view('orderspostingdetailsimi.deliverychalan', compact('ordersposting'));
        $pdf = PDF::loadView('tso.orader.pdf',  ['orader' => $orader, 'oraderdetails' => $oraderdetails, 'productList' => $productList, 'postings' => $postings], [], [
            'margin_top' => 20,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'margin_right' => 18,
            'format' => 'A4',
            'default_font_size' => '12',
        ]);
        return $pdf->stream('Invoice_' . '.pdf', 'I');
    }

    public function destroy($id)
    {


        $Orderdetail = Orderdetail::where('orader_number', '=', $id)->delete();
        $ordersposting = Order::find($id)->delete();

        return redirect()
            ->route('distributor.order')
            ->with('success', 'Draft Order  deleted successfully');
    }
}
