<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Cat;
use App\District;
use App\Division;
use App\Product;
use App\Upazila;
use App\User;
use Illuminate\Http\Request;

class AjaxSearchController extends Controller
{
    public function distributorSearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $distributors = User::where('level', 100)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'LIKE', '%' . $search . '%')
                    ->orWhere('officeid', 'LIKE', '%' . $search . '%');
            })
            ->limit(20)
            ->get();

        $results = [];

        foreach ($distributors as $distributor) {
            $results[] = [
                'id' => $distributor->id,
                'text' => $distributor->firstname . ' - ' . $distributor->officeid
            ];
        }

        return response()->json($results);
    }

    public function retailerSearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $retailers = User::where('level', 200)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'LIKE', '%' . $search . '%')
                    ->orWhere('officeid', 'LIKE', '%' . $search . '%');
            })
            ->limit(20)
            ->get();

        $results = [];

        foreach ($retailers as $retailer) {
            $results[] = [
                'id' => $retailer->id,
                'text' => $retailer->firstname . ' - ' . $retailer->officeid
            ];
        }

        return response()->json($results);
    }

    public function brandSearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $brands = Brand::where('name', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($brands as $brand) {
            $results[] = [
                'id' => $brand->id,
                'text' => $brand->name,
            ];
        }

        return response()->json($results);
    }

    public function categorySearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $categories = Cat::where('name', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($categories as $category) {
            $results[] = [
                'id' => $category->id,
                'text' => $category->name,
            ];
        }

        return response()->json($results);
    }

    public function productSearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $products = Product::where('model', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'text' => $product->model,
            ];
        }

        return response()->json($results);
    }
    public function divisionSearch(Request $request)
    {
        $search = $request->get('q');

        if (!$search) {
            return response()->json([]);
        }

        $divisions = Division::where('name', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($divisions as $division) {
            $results[] = [
                'id' => $division->id,
                'text' => $division->name,
            ];
        }

        return response()->json($results);
    }
    public function districtSearch(Request $request)
    {
        $search = $request->get('q');
        $divisionId = $request->get('division_id');

        if (!$search || !$divisionId) {
            return response()->json([]);
        }

        $districts = District::where('division_id', $divisionId)
            ->where('name', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($districts as $district) {
            $results[] = [
                'id' => $district->id,
                'text' => $district->name,
            ];
        }

        return response()->json($results);
    }
    public function upazilaSearch(Request $request)
    {
        $search = $request->get('q');
        $districtId = $request->get('district_id');

        if (!$search || !$districtId) {
            return response()->json([]);
        }

        $upazilas = Upazila::where('district_id', $districtId)
            ->where('name', 'LIKE', '%' . $search . '%')
            ->limit(20)
            ->get();

        $results = [];

        foreach ($upazilas as $upazila) {
            $results[] = [
                'id' => $upazila->id,
                'text' => $upazila->name,
            ];
        }

        return response()->json($results);
    }

}
