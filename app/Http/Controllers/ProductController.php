<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productService->index();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'brand_id'      => 'required',
            'cat_id'        => 'required',
            'name'          => 'required',
            'model'         => 'required',
            'product_code'  => 'required',
            'chalan_type'   => 'required',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
        ]);

        // ---------- Store via Service ----------
        $this->productService->store($request->all());

        return redirect()->route('products.index')->with('success', 'Product has been inserted successfully');
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
        $product = $this->productService->getById($id);
        return view('products.edit', compact('product'));
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
        // ---------- Validation ----------
        $this->validate($request, [
            'brand_id'      => 'required',
            'cat_id'   => 'required',
            'name'          => 'required',
            'model'         => 'required',
            'product_code'  => 'required',
            'chalan_type'   => 'required',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
        ]);

        $this->productService->update($id, $request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productService->destroy($id);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');

    }
}
