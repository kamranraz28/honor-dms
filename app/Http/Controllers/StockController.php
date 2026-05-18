<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fdate = $request->fdate;
        $tdate = $request->tdate;

        if ($fdate && $tdate) {
            $data = $this->stockService->filterStocksByDateRange($fdate, $tdate);
        } else {
            $data = $this->stockService->paginateStocks();
        }

        return view('stocks.index', [
            'stocks'   => $data['stocks'],
            'products' => $data['products'],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'imeis'      => 'required|array',
            'snos'       => 'required|array',
            'wperiods'   => 'required|array',
        ]);
        $this->stockService->createStock($validated);
        return redirect()->back()->with('success', 'Stock created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sno' => 'required',
            'imei' => 'required',
            'wperiod' => 'nullable',
        ]);
        $this->stockService->updateStock($id, $validated);
        return redirect()->back()->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->stockService->deleteStock($id);
        return redirect()->back()->with('success', 'Stock deleted successfully.');
    }

    public function filter(Request $request)
    {
        return redirect()->route('stocks.index', [
            'fdate' => $request->fdate,
            'tdate' => $request->todate,
        ]);
    }


}
