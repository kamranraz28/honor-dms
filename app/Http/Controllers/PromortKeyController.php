<?php

namespace App\Http\Controllers;

use App\Services\PromortkeyService;
use Illuminate\Http\Request;

class PromortKeyController extends Controller
{
    protected $promortkeyService;
    public function __construct(PromortkeyService $promortkeyService)
    {
        $this->promortkeyService = $promortkeyService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promortkeys = $this->promortkeyService->getAllPromortkeys();
        return view('promortkeys.index', compact('promortkeys'));
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
            'name' => 'string|required'
        ]);
        $this->promortkeyService->storePromortKey($validated);
        return redirect()->back()->with('success','Promort Key Stored Successfully.');
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
            'name' => 'string|required'
        ]);
        $this->promortkeyService->updatePromortKey($id,$validated);
        return redirect()->back()->with('success','Promort Key Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->promortkeyService->deletePromortKey($id);
        return redirect()->back()->with('success','Promort Key Deleted Successfully.');
    }

    public function statusChange(Request $request)
    {
        $this->promortkeyService->statusUpdate($request->id);
        return redirect()->back()->with('success','Promort Key Status Updated Successfully.');
    }
}
