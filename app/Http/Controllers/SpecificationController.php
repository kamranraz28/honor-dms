<?php

namespace App\Http\Controllers;

use App\Services\SpecificationService;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    protected $specificationService;
    public function __construct(
        SpecificationService $specificationService
    ){
        $this->specificationService = $specificationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specifications = $this->specificationService->getAllSpecifications();
        return view('specifications.index',compact('specifications'));
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
            'product_id' => 'required|integer|max:10',
            'name' => 'required|string|max:255',
            'specificationdetails' => 'required|string|max:255',
        ]);
        $this->specificationService->storeSpecification($validated);
        return redirect()->back()->with('success','Specification created successfully.');
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
            'product_id' => 'required|integer|max:10',
            'name' => 'required|string|max:255',
            'specificationdetails' => 'required|string|max:255',
        ]);
        $this->specificationService->updateSpecification($id, $validated);
        return redirect()->back()->with('success','Specification updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->specificationService->deleteSpecification($id);
        return redirect()->back()->with('success','Specification deleted successfully.');
    }
}
