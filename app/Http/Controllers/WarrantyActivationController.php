<?php

namespace App\Http\Controllers;

use App\Services\WarrantyActivationService;
use Illuminate\Http\Request;

class WarrantyActivationController extends Controller
{
    protected $warrantyActivationService;
    public function __construct(WarrantyActivationService $warrantyActivationService) {
        $this->warrantyActivationService = $warrantyActivationService;
    }
    public function activeWarranty()
    {
        return view('warranty.activeWarranty');
    }

    public function activeWarrantyStore(Request $request)
    {
        $validated = $request->validate([
            'retailer_id' => 'required|integer',
            'sno' => 'required|string',
            'mobile' => 'required|string',
            'fdate' => 'required|string',
        ]);
        $this->warrantyActivationService->activeWarranty($validated);
        return redirect()->back()->with('success','Warranty Activation data inserted successfully.');
    }
}
