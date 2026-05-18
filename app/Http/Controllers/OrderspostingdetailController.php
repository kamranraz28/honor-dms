<?php

namespace App\Http\Controllers;


use App\Orderspostingdetail;
use Illuminate\Http\Request;
use App\Models\Ordersposting;
use App\Order;
use Redirect;
use Validator;
use Input;
use Session;
use Auth;
use Storage;
use File;
use DB;
use Hash;

/**
 * Class OrderspostingdetailController
 * @package App\Http\Controllers
 */
class OrderspostingdetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderspostingdetails = Orderspostingdetail::paginate();

        return view('orderspostingdetail.index', compact('orderspostingdetails'))->with('i', (request()->input('page', 1) - 1) * $orderspostingdetails->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orderspostingdetail = new Orderspostingdetail();
        return view('orderspostingdetail.create', compact('orderspostingdetail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd( $request->all());
        //request()->validate(Orderspostingdetail::$rules);

        $rules = [
            'orader_number' => 'required|numeric|min:1',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|numeric|min:1',
            'quintity' => 'required|array|min:1',
            'quintity.*' => 'required|numeric|min:1',
            'unitprice' => 'required|array|min:1',
            'unitprice.*' => 'required|numeric|min:1',
            // Add more validation rules as needed
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator) // Send validation errors to the view
                ->withInput(); // Keep the old input data
        }

        $orader_number = $request->input('orader_number');
        $rdersposting = new Ordersposting();
        $rdersposting->orader_number = $orader_number;
        $rdersposting->save();

        $oradernumber = $rdersposting->id;
        $product_id = $request->input('product_id');
        $quantity = $request->input('quintity');
        $price = $request->input('unitprice');

        //dd($request->input('product_id'));

        for ($count = 0; $count < count($product_id); $count++) {
            $insert[] = [
                'orderspostings_id' => $oradernumber,
                'product_id' => $product_id[$count],
                'quantity' => $quantity[$count],
                'price' => $price[$count],
            ];
        }

        //dd( $insert);
        $orderspostingdetail = Orderspostingdetail::insert($insert);

        if ($orderspostingdetail) {
            Order::where('id', $orader_number)->update([
                'status' => 1,
            ]);
        }

        //Orderspostingdetail::create($request->all());
        if (Auth::user()->level == 10) {
            return redirect()
                ->route('tso.orader')
                ->with('message', 'Order successfully created.');
        } else {

            return redirect()
                ->route('distributor.order')
                ->with('message', 'Order successfully created.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderspostingdetail = Orderspostingdetail::find($id);

        return view('orderspostingdetail.show', compact('orderspostingdetail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $orderspostingdetail = Orderspostingdetail::find($id);

        return view('orderspostingdetail.edit', compact('orderspostingdetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Orderspostingdetail $orderspostingdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orderspostingdetail $orderspostingdetail)
    {
        request()->validate(Orderspostingdetail::$rules);

        $orderspostingdetail->update($request->all());

        return redirect()
            ->route('orderspostingdetails.index')
            ->with('success', 'Orderspostingdetail updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $orderspostingdetail = Orderspostingdetail::find($id)->delete();

        return redirect()
            ->route('orderspostingdetails.index')
            ->with('success', 'Orderspostingdetail deleted successfully');
    }
}
