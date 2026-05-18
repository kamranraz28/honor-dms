<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Orderspostingdetailsimi;
use App\Models\Ordersposting;
use App\Orderspostingdetail;
use App\Stock;
use App\Purchase;

class OrderimieditController extends Controller
{
  
    public function update(Request $request)
    {

         $data= Orderspostingdetailsimi::find($request->pk);      
         $product=Orderspostingdetail::find($data->orderspostingdetails_id);
         $imei=$request->value;
         //dd($data->orderspostingdetails_id,$data2, $data2->product_id);
            $checkstock = Stock::where('product_id', $product->product_id)->where(function ($query ) use ( $imei){
            $query->where('imei', '=', $imei)->orWhere('sno', '=', $imei);
            })->get()->toArray();
            $purchase = Purchase::where('product_id', $product->product_id)->where(function ($query ) use ( $imei){
            $query->where('imei', '=', $imei)->orWhere('sno', '=', $imei);
            })->get()->toArray();

        // $data= Ordersposting::where('orderspostings_id',$request->pk);
        //dd($product->id,$imei,$checkstock,$purchase);


        if ($request->ajax()) { 
            if( count($checkstock) && !$purchase){
                $model= Orderspostingdetailsimi::where('id',$request->pk)->update([ "imi"=>$request->value]);  
                return response()->json(['success' => true]);
             }else{
                return response()->json(['success' => false]);
            }
          
        }
    }
}
