<?php

namespace App\Http\Controllers;
 
use App\Models\Orderspostingdetailsimi;
use Illuminate\Http\Request;
use App\Models\Ordersposting;
use NumberFormatter;
use Illuminate\Support\Str;
use App\JobProgress;
use App\Jobs\DeliveryConfirmation;
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

use App\Stock;
use App\Order;
use App\Orderdetail;

use App\Division;
use App\District;
use App\Upazila;
use App\Middistrict;
use App\Tsoupazila;
use App\Orderspostingdetail;
use App\Purchase;
/**
 * Class OrderspostingdetailsimiController
 * @package App\Http\Controllers
 */
class OrderspostingdetailsimiController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderNumber = session::get('orderNumber');
        $queryarray = $queryarray = $request->query('search');
    
        if ($orderNumber && !$queryarray) {
            $ordersposting = Ordersposting::where('orader_number', $orderNumber)->paginate();
            $queryarray = $ordersposting->isNotEmpty() ? $ordersposting->first()->status : null;
        } elseif ($queryarray) {
            $ordersposting = Ordersposting::where('status', $queryarray)->orderBy('status')->paginate();
        } else {
            $ordersposting = Ordersposting::where('status', 1)->orderBy('status')->paginate();
        }
        // dd('1');
    
        return view('orderspostingdetailsimi.index', compact('ordersposting', 'queryarray'))
            ->with('i', (request()->input('page', 1) - 1) * $ordersposting->perPage());
    }
    

    public function orderSearch(Request $request)
    {
        $orderNumber = $request->input('order');
        session::put('orderNumber', $orderNumber);
        if (!$request->has('order')) {
            session::forget('orderNumber');
        }
        return redirect(route('orderspostingdetailsimis.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orderspostingdetailsimi = new Orderspostingdetailsimi();
        return view('orderspostingdetailsimi.create', compact('orderspostingdetailsimi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    
  
    public function store(Request $request)
    {

        $rules = [
            "orderspostingdetails_id"    => "required|numeric",
            "imi"    => "required|array|min:1",
            "imi.*"  => "required|unique:orderspostingdetailsimis,imi|alpha_num|min:5|",
        
         ];
         
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        $user_id = Auth::id();
        $orderspostingdetails_id = $request->input('orderspostingdetails_id');
        $orderspostingdetailsInfo = Orderspostingdetail::find($orderspostingdetails_id);
        // dd($orderspostingdetailsInfo);
        $orderpostings_id = $orderspostingdetailsInfo->orderspostings_id;
        // dd($orderpostings_id);
        $orderInfo = Ordersposting::find($orderpostings_id);
        $orderNumber = $orderInfo->orader_number;
        // dd($orderNumber);
        $productid = $orderspostingdetailsInfo->product_id;
        $imi = $request->input('imi');
        $icount = count($imi);

        $products = Stock::whereIn('imei', $imi)->orWhereIn('sno', $imi)->get();

        foreach ($products as $product) {
            if ($product->product_id != $productid) {
                return redirect()->back()->withErrors("You're trying to insert wrong product.")->withInput();
            }
        }
        
        $scount = Stock::whereIn('imei', $imi)->orWhereIn('sno', $imi)->distinct()->count('imei');

if ($icount > $scount) {
    return redirect()->back()->withErrors("You're trying to insert the same stock multiple times.")->withInput();
}

$existingImeis = Orderspostingdetailsimi::whereIn('imi', $imi)
    ->orWhereIn('imi2', $imi)
    ->pluck('imi')
    ->toArray();

foreach ($imi as $imei) {
    if (in_array($imei, $existingImeis)) {
        return redirect()->back()->withErrors("IMEI $imei has been sold.");
    }
}

        $totalOrderInfo = Orderspostingdetail::where('orderspostings_id', $orderpostings_id)->get();
        // dd($totalOrderInfo);

        $totalQuantity = $totalOrderInfo->sum('quantity');
        // dd($totalQuantity);


        // foreach ($imi as $imi_value) {
        //     $stockInfo = Stock::where('imei', $imi_value)->orWhere('sno', $imi_value)->get();
        //     $imi2 = $stockInfo->pluck('imei')->toArray();

        //     $data = [
        //         'orderspostingdetails_id' => $orderspostingdetails_id,
        //         'order_number' => $orderNumber,
        //         'product_id' => $productid,
        //         'imi' => $imi_value,
        //         'imi2' => implode(',', $imi2),
        //         'created_by' => $user_id,
        //     ];
        
        //     Orderspostingdetailsimi::create($data);
        // }

        $dataToCreate = [];

foreach (array_chunk($imi, 20) as $chunk) { // Adjust chunk size as needed
    $stockInfo = Stock::whereIn('imei', $chunk)->orWhereIn('sno', $chunk)->get();

    foreach ($chunk as $imi_value) {
        $filteredStockInfo = $stockInfo->filter(function ($item) use ($imi_value) {
            return $item->imei == $imi_value || $item->sno == $imi_value;
        });

        $imi2 = $filteredStockInfo->pluck('imei')->toArray();

        $dataToCreate[] = [ 
            'orderspostingdetails_id' => $orderspostingdetails_id,
            'order_number' => $orderNumber,
            'product_id' => $productid,
            'imi' => $imi_value,
            'imi2' => implode(',', $imi2),
            'created_by' => $user_id,
        ];
    }
}

Orderspostingdetailsimi::insert($dataToCreate);

        $givenQuantity = Orderspostingdetailsimi::where('order_number', $orderNumber)->count();
        // dd($givenQuantity);

        if($givenQuantity >= $totalQuantity){
            Order::find($orderNumber)->update(['status' => 3]);
            Ordersposting::where('orader_number', $orderNumber)->update(['status' => 3]);
        }else{
            Order::find($orderNumber)->update(['status' => 2]);
            Ordersposting::where('orader_number', $orderNumber)->update(['status' => 2]);
        }


        return redirect()->route('orderspostingdetailsimis.index')->with('success', 'Entry successfully created.');
    }

    public function PreeSellconfirmeation(Ordersposting $id, Request $request){

        $deliveryInfo = $request->input('delivery_info');
        $orderNumber = $id->orader_number;

        Order::find($orderNumber)->update(['status' => 5]);
        Ordersposting::where('orader_number', $orderNumber)->update(['status' => 5, 'delivery_info' => $deliveryInfo]);

        $jobId = (string) Str::uuid();

        // Initialize job tracking
        $job = JobProgress::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'type' => 'delivery_confirmation',
            'order_number' => $orderNumber,
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
        ]);

        dispatch(new DeliveryConfirmation($jobId,$id))->onQueue('delivery_confirmation');

        return redirect()->back()->with('success', "Delivery confirmation for Order: {$orderNumber} is successful.");
    }


// public function PreeSellconfirmeation(Ordersposting $id, Request $request){

//         $delivery_info = $request->input('delivery_info');
        
//         $orderNumber = $id->orader_number;
        
//         $orderspostingsdetailsimis = Orderspostingdetailsimi::select('imi', 'imi2')->where('order_number', $orderNumber)->get();
        
        
//         foreach ($orderspostingsdetailsimis as $order) {
//             $sno = $order->imi;
//             $imei = $order->imi2;
            
//             $distibutorid = $id->Order->usersd->id;
//             $districtId = $id->Order->usersd->district_id;
//             $upazilaId = $id->Order->usersd->upazila_id;
            
//             $stockInfo = Stock::select('id', 'brand_id', 'product_id')->where('sno', $sno)->orWhere('imei', $sno)->first();
            
//             if ($stockInfo) {
//                 $data['user_id'] = $distibutorid;
//                 $data['order_number'] = $orderNumber;
//                 $data['dis_id'] = $districtId;
//                 $data['up_id'] = $upazilaId;
//                 $data['stock_id'] = $stockInfo->id;
//                 $data['product_id'] = $stockInfo->product_id;
//                 $data['brand_id'] = $stockInfo->brand_id;
//                 $data['quantity'] = 1;
//                 $data['sno'] = $sno;
//                 $data['imei'] = $imei;
//                 $data['status'] = 0;
//                 $data['from_app'] = 0;
    
//                 Purchase::create($data);

//                 $stockInfo->update(['details' => 'sold']);
//             }
//         }
//         Order::find($orderNumber)->update(['status' => 5]);
//         Ordersposting::where('orader_number', $orderNumber)->update(['status' => 5, 'delivery_info' => $delivery_info]);
    
//         return redirect()->back()->with('success', 'Delivery Confirmed successfully');
//     }


    

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderspostingdetailsimi = Orderspostingdetailsimi::find($id);

        return view('orderspostingdetailsimi.show', compact('orderspostingdetailsimi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function add_pending_imei($id)
    {
        $orderspostingdetail = Orderspostingdetail::find($id);
        // dd($orderspostingdetail);

        $orderQuantity = $orderspostingdetail->quantity;
        // dd($orderQuantity);

        $existingQuantity = Orderspostingdetailsimi::where('orderspostingdetails_id', $id)->count();
        // dd($existingQuantity); //Count Existing IMEI

        $remainQuantity = $orderQuantity - $existingQuantity;
        // dd($remainQuantity);
        
        return view('orderspostingdetailsimi.pendingEdit', compact('orderspostingdetail','remainQuantity'));
    }
    
    public function edit($id)
    {
        $orderspostingdetail = Orderspostingdetail::find($id);
        // dd($orderspostingdetail);

        return view('orderspostingdetailsimi.edit', compact('orderspostingdetail'));
    }

    public function editexistingimi($id)
    {

       
    $oraders = Ordersposting::find($id);
    $oraderdetails=$oraders->OrderspostingDetails;
    //    $product = Orderspostingdetailsimi::where('orderspostingdetails_id',$id)->get(); 
    //    $orderspostingdetail=Orderspostingdetail::find($id);
             
        return view('orderspostingdetailsimi.editeximi', compact('oraderdetails','oraders'));
    }

    public function update(Request $request, Orderspostingdetailsimi $orderspostingdetailsimi)
    {
        request()->validate(Orderspostingdetailsimi::$rules);

        $orderspostingdetailsimi->update($request->all());

        return redirect()
            ->route('orderspostingdetailsimis.index')
            ->with('success', 'Orderspostingdetailsimi updated successfully');
    }

    public function deliverychalan($id)
    {
        $ordersposting = Ordersposting::find($id);
        $pdf = PDF::loadView('orderspostingdetailsimi.deliverychalan', compact('ordersposting'), [], [
            'margin_top' => 20,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'margin_right' => 18,
            'format' => 'A4',
            'default_font_size' => '12', 
         ]);
        return $pdf->stream('Invoice_'. '.pdf', 'I');
       
    }

    public function destroy($id)
    {

        abot(404);
        $orderspostingdetailsimi = Orderspostingdetailsimi::find($id)->delete();

        return redirect()
            ->route('orderspostingdetailsimis.index')
            ->with('success', 'Orderspostingdetailsimi deleted successfully');
    }


    public function deliveryInfo_edit(Ordersposting $id, Request $request)
    {
        $delivery_info = $request->input('delivery_info');
        // dd($delivery_info);
        Ordersposting::where('id', $id->id)->update(['delivery_info' => $delivery_info]);
        return redirect()->back()
            ->with('success', 'Delivery Information Updated successfully');
    }
}
