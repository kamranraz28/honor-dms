<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;
    public function __construct(PromotionService $promotionService) {
        $this->promotionService = $promotionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->promotionService->getAllPromotions();

        return view('promotions.index', [
            'products'   => $data['products'],
            'promos' => $data['promotions'],
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
            'name'      => 'required',
            'sdate'     => 'required|date',
            'edate'     => 'required|date',
            'status'    => 'required',
            'products'  => 'required|array',
            'amounts'   => 'required|array',
            'quantites' => 'required|array',
            'limits'    => 'required|array',
            'details'   => 'required|array',
        ]);
        $this->promotionService->storePromotion($validated);
        return redirect()->back()->with('success','Promotion created successfully.');
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
        $this->promotionService->updatePromotion($id, $request->all());
        return redirect()->back()->with('success','Promotion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->promotionService->deletePromotion($id);
        return redirect()->back()->with('success','Promotion deleted successfully.');
    }

    public function changeActiveStatus(Request $request)
    {
        $this->promotionService->updateStatus($request->input('id'));
        return redirect()->back()->with('success','Promotion status changed successfully.');
    }

    public function promotionDetails($id)
    {
        $data = $this->promotionService->promotionDetails($id);

        return view('promotions.promotionDetails', [
            'products'   => $data['products'],
            'promos' => $data['promotions'],
        ]);
    }
    public function PromoDetailsAdd(Request $request)
    {
        $validated = $request->validate([
            'promo_id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'limitperday' => 'required',
            'details' => 'required'
        ]);
        $this->promotionService->addPromotionDetails($validated);
        return redirect()->back()->with('success','Promotion details added successfully.');
    }
    public function PromoDetailsUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
            'quantity' => 'required',
            'limitperday' => 'required',
            'details' => 'required',
        ]);
        $this->promotionService->updatePromotionDetails($validated);
        return redirect()->back()->with('success','Promotion details updated successfully.');
    }
    public function ChangeStatusPromoDetails(Request $request)
    {
        $this->promotionService->changeStatusPromoDetails($id = $request->input('id'));
        return redirect()->back()->with('success','Promotion details status changed successfully.');
    }
    public function PromoDetailsDestroy($id)
    {
        $this->promotionService->promotionDetailsDelete($id);
        return redirect()->back()->with('success','Promotion details deleted successfully.');
    }


    public function promortView()
    {
        $promorts = $this->promotionService->getAllPromorts();
        return view('promotions.promort',compact('promorts'));
    }
    public function promortViewStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'sdate' => 'required',
            'edate' => 'required',
            'status' => 'required',
            'details' => 'nullable',
            'image' => 'nullable',
            'quantities' => 'required',
            'limits' => 'required',
        ]);
        $this->promotionService->storePromort($validated);
        return redirect()->back()->with('success','Promort Added successfully.');
    }
    public function PromortUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required',
            'sdate' => 'required',
            'edate' => 'required',
            'image' => 'nullable',
        ]);
        $this->promotionService->updatePromort($validated);
        return redirect()->back()->with('success','Promort updated successfully.');
    }
    public function PromortDestroy($id)
    {
        $this->promotionService->deletePromort($id);
        return redirect()->back()->with('success','Promort deleted successfully.');
    }
    public function promortDetails($id)
    {
        $promorts = $this->promotionService->promortDetails($id);
        return view('promotions.promortDetails',compact('promorts'));
    }
    public function promortDetailsAdd(Request $request)
    {
        $validated = $request->validate([
            'promort_id' => 'required',
            'quantity' => 'required',
            'limitperday' => 'required',
            'details' => 'required'
        ]);
        $this->promotionService->storePromortDetail($validated);
        return redirect()->back()->with('success','Promort details added successfully.');
    }
    public function promortDetailsUpdate(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'quantity' => 'required',
            'limitperday' => 'required',
            'details' => 'required'
        ]);
        $this->promotionService->updatePromortDetail($validated);
        return redirect()->back()->with('success','Promort details updated successfully.');
    }
    public function promortDetailsDestroy($id)
    {
        $this->promotionService->deletePromortDetails($id);
        return redirect()->back()->with('success','Promort details deleted successfully.');
    }
    public function changeActiveStatusPromortDetails(Request $request)
    {
        $this->promotionService->changeStatusPromortDetails($id = $request->input('id'));
        return redirect()->back()->with('success','Promort details status changed successfully.');
    }
    public function ChangeActiveStatusPromort(Request $request)
    {
        $this->promotionService->updatePromortStatus($request->input('id'));
        return redirect()->back()->with('success','Promort status changed successfully.');
    }
}
