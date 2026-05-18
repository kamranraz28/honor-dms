<?php

namespace App\Http\Controllers;

use App\Services\RetailerService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    protected $retailerService;
    public function __construct(
        UserService $userService,
        RetailerService $retailerService
    ) {
        $this->userService = $userService;
        $this->retailerService = $retailerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $data = $this->userService->getUserPageData();

        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->userService->create();
        $divisions = $data['divisions'];
        $districts = $data['districts'];
        $upazilas = $data['upazilas'];
        return view('users.create',compact('divisions', 'districts', 'upazilas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $this->userService->storeUser($request);

        return redirect()->route('users.index')->with('success', 'User has been created successfully');
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
        $data = $request->all();
        $this->userService->updateUser($id, $data);
        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function userDownload()
    {
        return $this->userService->userDownload();
    }

    public function InactiveRetailerView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $userResult = $this->userService->inactiveRetailers();

        return view('users.inactiveRetailer', ['retailers' => $userResult]);
    }
    public function UpdateOfficeid(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'officeid' => 'required|string',
            'confirm_officeid' => 'required|string|same:officeid',
        ]);
        $this->userService->updateUserId($validated);
        return redirect()->back()->with('success', 'Office ID updated successfully.');
    }

    public function UpdatePassword(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password',
        ]);
        $this->userService->updateUserPassword($validated);
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
    public function changeActiveStatus(Request $request)
    {
        $this->userService->changeUserStatus($request->id);
        return redirect()->back()->with('success', 'User status changed successfully.');
    }
    public function changeAbleStatus(Request $request)
    {
        //dd($request->all());
        $this->userService->changeUserAbleStatus($request->id);
        return redirect()->back()->with('success', 'User status changed successfully.');
    }
    public function RetailerView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $data = $this->retailerService->getRetailerPageData();

        return view('users.retailer', $data);
    }

    public function RetailerCreate()
    {
        $data = $this->userService->create();
        $divisions = $data['divisions'];
        $districts = $data['districts'];
        $upazilas = $data['upazilas'];
        return view('users.retailer-create',compact('divisions', 'districts', 'upazilas'));
    }
    public function RetailerViewStore(Request $request)
    {
        $validated = $request->validate([
            'division_id' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'firstname' => 'required|min:2|max:50',
            'contact_name' => 'required|min:2|max:50',
            'market_name' => 'required|min:2|max:50',
            'store_type' => 'required|min:2|max:50',
            'email' => 'required|email|unique:users',
            'officeid' => 'required|unique:users',
            'password' => 'required|min:3|max:20',
            'confirm_password' => 'required|min:3|max:20|same:password',
            'contact' => 'required|numeric|min:1',
            'address' => 'required|min:2|max:99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
        ]);

        $this->retailerService->store($validated, $request);

        return redirect()->route('admin.retailer')->with('success', 'Retailer has been inserted successfully');
    }

    public function retailerDownload()
    {
        return $this->userService->retailerDownload();
    }
}
