<?php

namespace App\Http\Controllers;

use App\Models\Ordersposting;
use App\Models\Orderspostingdetailsimi;
use App\Orderspostingdetail;
use App\PrimaryTransfer;
use App\Orderdeletelog;
use App\Services\BulkUploadService;
use App\Services\DashboardService;
use App\Services\OrderPostingService;
use App\Services\ReportService;
use App\Services\RetailerService;
use App\Services\UserService;
use Illuminate\Http\Request;

use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;
use App\Jobs\GenerateRetailerImeiStockReport;
use App\Jobs\DailySalesReport;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Rap2hpoutre\FastExcel\FastExcel;

use Redirect;
use Validator;
use Input;
use Session;
use Auth;
use Storage;
use File;
use DB;
use Hash;

use App\User;
use App\Retailer;
use App\Setting;
use App\Brand;
use App\Cat;
use App\Product;
use App\Specification;
use App\Stock;
use App\Vatreport;
use Carbon\Carbon;
use App\Promo;
use App\Promodetail;

use App\Promort;
use App\Transfer;
use App\Promortdetail;
use App\Promortretailer;
use App\Promortkey;
use App\Promortsmsdetail;
use App\Smsdetail;
use App\Dwdetail;
use App\Replace;
use App\Sr;
use App\Purchase;
use App\Sale;
use App\Preturn;
use App\Division;
use App\District;
use App\Upazila;
use App\Middistrict;
use App\Tsoupazila;
use App\Target;
use App\Service;
use Illuminate\Support\Facades\Cache;

use App\Order;
use App\JobProgress;
use App\Jobs\ImeiBulkUpload;
use App\Jobs\PrimarySalesBulkUpload;
use App\Jobs\StockBulkUpload;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public static $code;
    public static $currency;
    public static $timezone;
    public static $contact;
    public static $vat;
    public static $semail;
    public static $favicon;
    public static $logo;
    protected $bulkUploadService;
    protected $orderPostingService;
    protected $retailerService;
    protected $reportService;
    protected $dashboardService;
    protected $userService;


    public function __construct(
        BulkUploadService $bulkUploadService,
        OrderPostingService $orderPostingService,
        RetailerService $retailerService,
        ReportService $reportService,
        DashboardService $dashboardService,
        UserService $userService
    ) {
        $this->bulkUploadService = $bulkUploadService;
        $this->orderPostingService = $orderPostingService;
        $this->retailerService = $retailerService;
        $this->reportService = $reportService;
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
        $this->middleware('auth')->except(['Test']);

        $settingResult = Setting::first();
        $settings = $settingResult->toArray();


        self::$code = $settings['code'];
        self::$currency = $settings['currency'];
        self::$timezone = $settings['timezone'];
        self::$vat = $settings['vat'];
        self::$contact = $settings['contact'];
        self::$semail = $settings['semail'];
        self::$favicon = $settings['favicon'];
        self::$logo = $settings['logo'];

        date_default_timezone_set(self::$timezone);

    }

    protected function security()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
    }

    public function generateReportShow()
    {
        $prompts = DB::select('SELECT * FROM prompts ORDER BY id DESC');

        return view('generate-order-report', compact('prompts'));
    }

    public function DashboardView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $_SESSION['favicon'] = self::$favicon;
        $_SESSION['logo'] = self::$logo;

        $dashboard = $this->dashboardService->getDashboardData();

        return view('admin.dashboard', $dashboard);
    }

    // ajax code ===============

    public function DistrictSelectBoxOnDivisionWithAjax($id)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        $districtResult = District::where('division_id', $id)->get();
        return $districts = $districtResult->toArray();
    }


    public function UpazilaSelectBoxOnDistrictWithAjax($id)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        $upazilaResult = Upazila::where('district_id', $id)->get();
        return $upazilas = $upazilaResult->toArray();
    }

    public function DistrictSelectBoxOnRetailerWithAjax($id)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        $userResult = User::where(['district_id' => $id, 'level' => 200])->get();
        return $users = $userResult->toArray();
    }

    public function UpazilaSelectBoxOnRetailerWithAjax($id)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        $userResult = User::where(['upazila_id' => $id, 'level' => 200])->get();
        return $users = $userResult->toArray();
    }

    public function DistributorSelectBoxOnRetailerWithAjax($id)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        $userResult = retailer::where(['user_id' => $id])->get();
        return $users = $userResult->toArray();
    }

    public function Dontworryimeikeyup($imei = null)
    {
        //if (Auth::user()->level != 500) { return redirect()->route('logout');}
        //$userResult = User::where(['upazila_id'=>$id,'level'=>200])->get();
        //return $users = $userResult->toArray();

        $count = Dwdetail::where(['sno' => $imei])->count();

        if ($count > 0) {
            return $arrayName = array('dwdublicate' => 1, 'dwstatus' => 0, 'slstatus' => 0, 'wperiod' => 0, 'product' => null, 'dwcharge' => 0, 'dwday' => 0, 'dwduration' => 0);
        } else {

            $count = Stock::where(['sno' => $imei])->count();
            if ($count > 0) {
                $stockResult = Stock::where(['sno' => $imei])->first();
                $stocks = $stockResult->toArray();
                $product_id = $stocks["product_id"];


                $count = Product::where(['id' => $product_id, 'dwstatus' => 1])->count();

                if ($count > 0) {
                    $productResult = Product::where(['id' => $product_id, 'dwstatus' => 1])->first();
                    $products = $productResult->toArray();

                    $product = $products["name"] . " - " . $products["model"];
                    $dwcharge = $products["dwcharge"];
                    $dwday = $products["dwday"];
                    $dwduration = $products["dwduration"];


                    $count = Smsdetail::where(['sno' => $imei])->count();

                    if ($count > 0) {

                        $smsdetailResult = Smsdetail::where(['sno' => $imei])->first();
                        $smsdetails = $smsdetailResult->toArray();
                        $created_at = $smsdetails["created_at"];

                        $date1 = strtotime(date_format(date_create($created_at), "Y-m-d"));
                        $date2 = strtotime(date_format(date_create(date("Y-m-d h:i:s")), "Y-m-d"));
                        $wperiod = ($date2 - $date1) / 86400;

                        return $arrayName = array('dwdublicate' => 0, 'dwstatus' => 1, 'slstatus' => 1, 'wperiod' => $wperiod, 'product' => $product, 'dwcharge' => $dwcharge, 'dwday' => $dwday, 'dwduration' => $dwduration);
                    } else {
                        return $arrayName = array('dwdublicate' => 0, 'dwstatus' => 1, 'slstatus' => 0, 'wperiod' => 0, 'product' => $product, 'dwcharge' => $dwcharge, 'dwday' => $dwday, 'dwduration' => $dwduration);
                    }


                } else {
                    return $arrayName = array('dwdublicate' => 0, 'dwstatus' => 0, 'slstatus' => 0, 'wperiod' => 0, 'product' => null, 'dwcharge' => 0, 'dwday' => 0, 'dwduration' => 0);
                }
            } else {
                return $arrayName = array('dwdublicate' => 0, 'dwstatus' => 0, 'slstatus' => 0, 'wperiod' => 0, 'product' => null, 'dwcharge' => 0, 'dwday' => 0, 'dwduration' => 0);
            }
        }
    }


    // ajax code ===============

    public function SingleUser($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $requestRetailerCount = User::where(['active' => 0, 'status' => 0, 'level' => 200])->orderBy('id', 'desc')->count();

        $_SESSION['requestRetailerCount'] = $requestRetailerCount;

        $users = User::with('retailer', 'sr', 'division', 'district', 'upazila', 'middistrict', 'tsoupazila')->where('id', $id)->where('level', '!=', 200)->orderBy('level', 'desc')->paginate(1);

        //dd($users);



        $userResult = User::select('id', 'firstname', 'officeid')->where('level', 200)->orderBy('id', 'desc')->get();
        $retailers = $userResult->toArray();

        $userResult = User::select('id', 'firstname', 'officeid')->where('level', 50)->orderBy('id', 'desc')->get();
        $srs = $userResult->toArray();

        $userResult = User::select('id', 'firstname', 'officeid')->where('level', 100)->orderBy('id', 'desc')->get();
        $distributors = $userResult->toArray();


        $divisionResult = Division::get();
        $divisions = $divisionResult->toArray();

        $districtResult = District::get();
        $districts = $districtResult->toArray();

        $upazilaResult = Upazila::get();
        $upazilas = $upazilaResult->toArray();

        return view('admin.singleuser', [
            'users' => $users,
            'retailers' => $retailers,
            'srs' => $srs,
            'divisions' => $divisions,
            'districts' => $districts,
            'upazilas' => $upazilas,
            'distributors' => $distributors
        ]);
    }


    public function AddRetailer(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $this->validate($request, [
            'user_id' => 'required',
            'retailers' => 'required',
        ]);

        $user_id = $request->user_id;
        $retailers = $request->retailers;


        foreach ($retailers as $key => $retailer) {

            $count = Retailer::where(['user_id' => $user_id, 'retailer_id' => $retailer])->count();

            if ($count > 0) {
                return redirect()->back()->withErrors("Same retailer can not be added")->withInput();
            }
        }

        foreach ($retailers as $key => $retailer) {

            $user = User::where('id', $retailer)->take(1)->first();

            $data['user_id'] = $user_id;
            $data['retailer_id'] = $user->id;
            $data['name'] = $user->firstname;
            $data['email'] = $user->email;
            $data['officeid'] = $user->officeid;

            Retailer::create($data);
        }

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }

    public function deleteRetailer($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        if ($id == null) {
            return redirect()->back()->withErrors("Retailer can not be deleted")->withInput();
        }
        $count = Sale::where(['retailer_id' => $id])->count();

        if ($count > 0) {
            return redirect()->back()->withErrors('This user can not be deleted due to related to other data');
        }

        DB::table('retailers')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully');

    }


    public function CheckRetailerView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $requestRetailerCount = User::where(['active' => 0, 'status' => 0, 'level' => 200])->orderBy('id', 'desc')->count();

        $_SESSION['requestRetailerCount'] = $requestRetailerCount;


        $userCount = User::count();

        $officeid = Session::get('officeid');

        $ssdata = [];
        $totalamount = [];
        $retailers = [];

        if ($officeid) {

            $ssdata['officeid'] = $officeid;

            $count1 = User::where(['level' => 200, 'email' => $officeid])->count();
            $count2 = User::where(['level' => 200, 'officeid' => $officeid])->count();

            if ($count1 > 0) {
                $retailers = User::with('division', 'district', 'upazila')->where(['level' => 200, 'email' => $officeid])->paginate(1);
            } elseif ($count2 > 0) {
                $retailers = User::with('division', 'district', 'upazila')->where(['level' => 200, 'officeid' => $officeid])->paginate(1);
            } else {
                $retailers = [];
            }

            //dd($retailers);

        }

        $divisionResult = Division::get();
        $divisions = $divisionResult->toArray();

        $districtResult = District::get();
        $districts = $districtResult->toArray();

        $upazilaResult = Upazila::get();
        $upazilas = $upazilaResult->toArray();


        //Session::forget(['user_id','fdate','todate']);

        return view(
            'admin.checkRetailer',
            [
                'ssdata' => $ssdata,
                'retailers' => $retailers,
                'totalamount' => $totalamount,
                'divisions' => $divisions,
                'districts' => $districts,
                'upazilas' => $upazilas
            ]
        );

    }


    public function CheckRetailerViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        Session::forget(['officeid']);

        $this->validate($request, [
            'officeid' => 'required'
        ]);

        $officeid = $request->get('officeid');

        Session::put(['officeid' => $officeid]);

        return redirect(route('admin.user.CheckRetailer'));


    }

    public function UserUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                'firstname' => 'required|min:2|max:50',
                //'lastname' => 'required|min:1|max:50',
                //'officeid' => 'required|unique:users',
                'contact' => 'required|numeric|min:1',
            ]);

            $image = $request->file('image');


            $division_id = $request->get('division_id');
            $district_id = $request->get('district_id');
            $upazila_id = $request->get('upazila_id');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                $user->address = $request->get('address');
                $user->dis_cat = $request->get('dis_cat');

                //$user->officeid = $request->get('officeid');
                $user->contact_name = $request->get('contact_name');
                $user->contact = $request->get('contact');
                $user->photo = $image_name;

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;

                $user->save();

                //=================================================================

            } else {
                //$image_name = NULL;

                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->address = $request->get('address');
                $user->dis_cat = $request->get('dis_cat');
                $user->contact_name = $request->get('contact_name');

                $user->contact = $request->get('contact');

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;

                $user->save();

            }

            return redirect()->back()->with('success', 'Data has been updated successfully');

        }

    }

    public function UserDisable(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];

        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            $user->dist_return = false;
            $user->save();
            return redirect()->back()->with('success', 'Data has been inactivated successfully');
        }

    }

    public function UserEnable(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];

        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            $user->dist_return = true;
            $user->save();
            return redirect()->back()->with('success', 'Data has been activated successfully');
        }

    }

    public function UpdateEmail(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $id = $request->get('id');
        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                'email' => 'required|email|min:3|max:100|unique:users'
            ]);

            $user->email = $request['email'];
            $user->save();

        }

        return redirect()->back()->with('success', 'Email has been updated successfully');

    }

    public function UpdateAlternativeEmail(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $id = $request->get('id');
        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                'alemail' => 'required|email|min:3|max:100|unique:users'
            ]);

            $user->alemail = $request['alemail'];
            $user->save();

        }

        return redirect()->back()->with('success', 'Email has been updated successfully');

    }

    public function UserDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $user = User::find($id);
        $smsdetailCount = Smsdetail::where(['user_id' => $id])->count();
        $retailerCount = Retailer::where(['user_id' => $id])->count();
        $srCount = Sr::where(['user_id' => $id])->count();

        $middistrictCount = Middistrict::where(['user_id' => $id])->count();


        $retailerCount2 = Retailer::where(['retailer_id' => $id])->count();
        $srCount2 = Sr::where(['sr_id' => $id])->count();

        if ($smsdetailCount > 0 || $retailerCount > 0 || $srCount > 0 || $retailerCount2 > 0 || $srCount2 > 0 || $middistrictCount > 0) {
            return redirect()->back()->withErrors('This user can not be deleted due to related to other data');
        }

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {

            $photo = $user->photo;

            if (!is_null($photo)) {
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================
            }
            //====================================

            $retailerCount1 = Retailer::where(['retailer_id' => $id])->count();
            if ($retailerCount1 > 0) {
                DB::table('retailers')->where('retailer_id', $id)->delete();
            }

            $srCount1 = Sr::where(['sr_id' => $id])->count();
            if ($srCount1 > 0) {
                DB::table('srs')->where('sr_id', $id)->delete();
            }
            //====================================


            $user->delete();
            return redirect()->back()->with('success', 'Data has been deleted successfully');
        }

    }

    public function SettingView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //$settingCount = Setting::count();

        $settingResult = Setting::orderBy('id', 'desc')->get();
        $settings = $settingResult->toArray();

        return view('admin.setting', ['settings' => $settings]);

    }

    public function SettingUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $setting = Setting::find($id);

        if ($setting === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, ['currency' => 'required', 'code' => 'required', 'timezone' => 'required', 'hotline' => 'required', 'contact' => 'required', 'vat' => 'required', 'semail' => 'required']);

            $image = $request->file('image');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $setting['favicon']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $setting->favicon = $image_name;

            }

            $image = $request->file('image1');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $setting['logo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $setting->logo = $image_name;

            }


            $setting->currency = $request->get('currency');
            $setting->code = $request->get('code');
            $setting->timezone = $request->get('timezone');
            $setting->hotline = $request->get('hotline');
            $setting->contact = $request->get('contact');
            $setting->vat = $request->get('vat');
            $setting->semail = $request->get('semail');
            $setting->save();

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }

    }


    public function ProductDontWorryInactive(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];
        $user = Product::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $user->dwstatus = false;
            $user->save();
            return redirect()->back()->with('success', 'Product has been inactivated successfully');
        }

    }

    public function ProductDontWorryActive(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];
        $user = Product::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $user->dwstatus = true;
            $user->save();
            return redirect()->back()->with('success', 'Product has been activated successfully');
        }

    }

    // Product =======================================


    // Promo =======================================

    public function PromortRetailerDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $promortretailer = Promortretailer::find($id);

        $userCount = 0;
        //$userCount = User::where('promort_id', $id)->count();
        //$user = User::where('promort_id', $id)->get();

        if ($promortretailer === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($userCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with user information');
            } else {
                $promortretailer->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }

    // new  code

    public function PromortRetailerAdd(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $this->validate($request, [
            'promort_id' => 'required',
            'users' => 'required'
        ]);

        //dd($request->all());

        $users = $request->users;
        $promort_id = $request->promort_id;
        $data['promort_id'] = $request->promort_id;
        foreach ($users as $key => $value) {

            $count = Promortretailer::where(['user_id' => $value, 'promort_id' => $promort_id])->count();

            if ($count < 1) {
                $data['user_id'] = $value;
                Promortretailer::create($data);
            }

        }
        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }



    public function ChangeActiveStatusPromort(Request $request)
    {

        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $this->validate($request, ['id' => 'required', 'status' => 'required']);

        $id = $request->get('id');
        $status = $request->get('status');

        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $promort = Promort::find($id);
        if ($promort === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $promort->status = $status;
            $promort->save();
            //---------------------
            DB::table('promortdetails')->where('promort_id', $id)->update(['status' => $status]);
            //---------------------

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }

    }


    public function ChangeActiveStatusPromortDetails(Request $request)
    {

        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        $this->validate($request, ['id' => 'required', 'status' => 'required']);

        $id = $request->get('id');
        $status = $request->get('status');

        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $promortdetail = Promortdetail::find($id);
        if ($promortdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $promortdetail->status = $status;
            $promortdetail->save();

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }

    }

    public function Upload1View()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        return view('admin.upload1');

    }

    public function Upload1ViewStore(Request $request)
    {
        if (Auth::user()->level !== 500) {
            return redirect()->route('logout');
        }

        $request->validate([
            'type' => 'required|integer',
            'csv_file' => 'required|mimes:csv,txt|max:200000',
        ]);

        try {
            [$status, $message] = $this->bulkUploadService->handle(
                (int) $request->type,
                $request->file('csv_file'),
                Auth::user()
            );

            return redirect()->back()->with($status, $message);

        } catch (\Throwable $e) {
            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    private static function send_msg($mobileno, $msg)
    {
        $phoneno = str_replace("+", "", $mobileno);
        $getdata = http_build_query(
            array(
                'masking' => 'SMART TECH',
                'userName' => 'SmartTech_Sofel',
                'password' => '46fb610d839ea46f08f7ab8810686e19',
                'MsgType' => 'TEXT',
                'receiver' => $phoneno,
                'message' => $msg,
            )
        );

        $opts = array(
            'http' =>
                array(
                    'method' => 'GET',
                    'header' => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => $getdata
                )
        );

        $context = stream_context_create($opts);

        file_get_contents('http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?' . $getdata, false, $context);

    }

    private static function jhorotek_sms_service($mobile, $smsBody, $user = 'miranrulzz@gmail.com', $pass = '@Syngpsil%$', $masking = 'GoPrep')
    {

        $sms_array = array();

        //create a json array of your sms
        $row_array['trxID'] = self::udate('YmdHisu');
        $row_array['trxTime'] = date('Y-m-d H:i:s');

        $mySMSArray[0]['smsID'] = self::udate('YmdHisu');
        $mySMSArray[0]['smsSendTime'] = date('Y-m-d H:i:s');
        $mySMSArray[0]['mask'] = $masking;
        //$mySMSArray [0]['mobileNo'] = '8801777001014';
        //$mySMSArray [0]['smsBody'] = 'Testing from infobuzzer to Ringku';

        $mySMSArray[0]['mobileNo'] = $mobile;
        $mySMSArray[0]['smsBody'] = $smsBody;


        $row_array['smsDatumArray'] = $mySMSArray;

        $myJSonDatum = json_encode($row_array);

        //specifi the url
        //$url="http://api.infobuzzer.net/v3.1/SendSMS/sendSmsInfoStore";
        $url = "http://api.infobuzzer.net/v3.1/index.php/SendSMS/sendSmsInfoStore";

        if ($ch = curl_init($url)) {
            //Your valid username & Password ----------Please update those field
            //$username = 'info@synergyinterface1.com';
            //$password = 'info@Pass';


            $username = $user;
            $password = $pass;

            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($myJSonDatum)
                )
            );

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $myJSonDatum);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            $response = curl_exec($ch);
            curl_close($ch);
            //return $json = json_decode($response, true);
            //echo('Response is: '.$response);

            //$json['status'] ." ".$json['success']." ".$json['reason'];
            //return  "Response is" . $json;
        } else {
            return "Sorry,the connection cannot be established";
        }


    }


    private static function udate($format, $utimestamp = null)
    {
        $m = explode(' ', microtime());
        list($totalSeconds, $extraMilliseconds) = array($m[1], (int) round($m[0] * 1000, 3));
        return date("YmdHis", $totalSeconds) . sprintf('%03d', $extraMilliseconds);
    }

    public function RetailerdwnldView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();

        $requestRetailerdwnldCount = User::where(['active' => 0, 'status' => 0, 'level' => 200])->orderBy('id', 'desc')->count();

        $_SESSION['requestRetailerdwnldCount'] = $requestRetailerdwnldCount;

        //$userResult = User::with('territory')->get();
        $userResult = User::with('division', 'district', 'upazila')->where('level', 200)->orderBy('id', 'desc')->paginate(10000);
        //$users = $userResult->toArray();

        //dd($userResult);
//

        $divisionResult = Division::get();
        $divisions = $divisionResult->toArray();

        $districtResult = District::get();
        $districts = $districtResult->toArray();

        $upazilaResult = Upazila::get();
        $upazilas = $upazilaResult->toArray();

        return view('admin.retailerdwnld', ['retailerdwnlds' => $userResult, 'divisions' => $divisions, 'districts' => $districts, 'upazilas' => $upazilas]);

    }

    public function RetailerUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                'firstname' => 'required|min:2|max:50',
                'contact_name' => 'required|min:2|max:50',
                'market_name' => 'required|min:2|max:50',
                'store_type' => 'required|min:2|max:50',
                //'lastname' => 'required|min:1|max:50',
                'officeid' => 'required|unique:users',
                'contact' => 'required|numeric|min:1',
                'address' => 'required|min:2|max:99',
            ]);


            $image = $request->file('image');
            $division_id = $request->get('division_id');
            $district_id = $request->get('district_id');
            $upazila_id = $request->get('upazila_id');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->contact_name = $request->get('contact_name');
                $user->market_name = $request->get('market_name');
                $user->store_type = $request->get('store_type');
                //$user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                $user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->address = $request->get('address');
                $user->photo = $image_name;

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;

                $user->save();

                //=================================================================

            } else {
                //$image_name = NULL;

                //=================================================================
                $user->firstname = $request->get('firstname');
                $user->lastname = $request->get('lastname');
                $user->contact_name = $request->get('contact_name');
                $user->market_name = $request->get('market_name');
                $user->store_type = $request->get('store_type');
                //$user->email = $request->get('email');
                $user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->address = $request->get('address');
                //$user->photo = $image_name;

                $user->division_id = $division_id;
                $user->district_id = $district_id;
                $user->upazila_id = $upazila_id;

                $user->save();

            }

            return redirect()->back()->with('success', 'Data has been updated successfully');

        }


    }

    public function WcheckProductView()
    {



        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }


        //Session::forget(['imei']);
        $ssdata = [];
        $data = [];
        $dataCount = 0;
        $imei = Session::get('imei');


        if ($imei) {
            $ssdata['imei'] = $imei;
            $dataCount = 1;

            $smsdetailCount = Smsdetail::where(['imei' => $imei])->orWhere(['sno' => $imei])->count();
            if ($smsdetailCount > 0) {
                // dd($data);
                $query = Smsdetail::with('product', 'replace', 'service')->select(
                    'id',
                    'product_id',
                    'promo_id',
                    'promodetail_id',
                    'sno',
                    'imei',
                    'wperiod',
                    'iswar',
                    DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
                        DATE_FORMAT(created_at,"%m/%d/%Y") as sdate,
                        DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate')
                )

                    ->where(['imei' => $imei])
                    ->orWhere(['sno' => $imei])
                    //->take(1)
                    ->get();

                $data = json_decode(json_encode($query), True);

            }

        }

        return view('admin.wcheckProduct', ['ssdata' => $ssdata, 'wcheckProducts' => $data, 'dataCount' => $dataCount]);

    }


    public function WcheckProductViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        Session::forget(['imei']);

        $this->validate($request, [
            'imei' => 'required'
        ]);

        $imei = $request->get('imei');

        Session::put(['imei' => $imei]);

        return redirect(route('admin.wcheckProduct'));
    }


    public function WcheckProductReplace(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'cn' => 'required',
            //'imei' => 'required',
            'mobile' => 'required',
            'service_type' => 'required',
            //'imei' => 'required',
            'problem' => 'required',
        ]);

        $id = $request->get('id');
        $smsdetail = Smsdetail::find($id);

        if ($smsdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }
        $request1['user_id'] = Auth::user()->id;
        $request1['smsdetail_id'] = $request->id;
        $request1['imei'] = $smsdetail->imei;
        $request1['sno'] = $smsdetail->sno;
        $request1['contact_name'] = $request->cn;
        $request1['contact_no'] = $request->mobile;
        $request1['service_type'] = $request->service_type;
        $request1['problem'] = $request->problem;
        $request1['brand_id'] = $smsdetail->brand_id;
        $request1['product_id'] = $smsdetail->product_id;
        $image = $request->file('image');

        if (!is_null($image)) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]);


            $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
            Storage::put($image_name, file_get_contents($image));
        } else {
            $image_name = NULL;
        }

        $request1['memo'] = $image_name;

        Replace::create($request1);

        $smsdetail->imei = $request->get('imei');
        $smsdetail->sno = $request->get('sno');
        $smsdetail->iswar = 0;

        $smsdetail->save();

        return redirect()->back()->with('success', 'Data has been inserted successfully');
    }

    public function WcheckProductService(request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'cn' => 'required',
            //'imei' => 'required',
            'mobile' => 'required',
            'service_type' => 'required',
            //'imei' => 'required',
            'problem' => 'required',
        ]);


        $id = $request->get('id');
        $smsdetail = Smsdetail::find($id);

        if ($smsdetail === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }

        $request1['smsdetail_id'] = $request->id;
        $request1['imei'] = $smsdetail->imei;
        $request1['sno'] = $smsdetail->sno;
        $request1['contact_name'] = $request->cn;
        $request1['contact_no'] = $request->mobile;
        $request1['service_type'] = $request->service_type;
        $request1['problem'] = $request->problem;

        Service::create($request1);

        return redirect()->back()->with('success', 'Data has been inserted successfully');

    }


    public function WcheckProductReplaceUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }


        $this->validate($request, [
            'id' => 'required',
            //'imei' => 'required',
            'sno' => 'required',
        ]);


        $id = $request->get('id');
        $Replace = Replace::find($id);

        if ($Replace === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        }

        //-------------------
        $Replace->imei = $request->imei;
        $Replace->sno = $request->sno;
        $Replace->save();
        //-------------------

        return redirect()->back()->with('success', 'Data has been update successfully');

    }

    public function WcheckProductReplaceDelete($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Replace = Replace::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Replace === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {

                //--------------------------
                $smsdetail_id = $Replace->smsdetail_id;
                DB::table('smsdetails')->where('id', $smsdetail_id)->update(['iswar' => 1]);
                //--------------------------

                $Replace->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }

        }

    }


    public function RetailerCheckReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        return view('admin.retailerCheckReport');
    }

    public function RetailerCheckReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $distributor_id = $request->distributor_id;

        $retailersQuery = Retailer::with('user');

        if ($distributor_id) {
            $retailersQuery->where('user_id', $distributor_id);
        }

        $retailers = $retailersQuery->get();

        $exportData = [];

        foreach ($retailers as $key => $retailer) {
            $exportData[] = [
                '#' => $key + 1,
                'Distributor Code' => $retailer->dealer->officeid ?? '-',
                'Distributor Name' => $retailer->dealer->firstname ?? '-',
                'Retailer Code' => $retailer->officeid ?? '-',
                'Retailer Name' => $retailer->name ?? '-',
                'Mapping Date' => optional($retailer->created_at)->format('d-m-Y H:i'),
            ];
        }

        $fileName = 'Retailer_Mapping_Report_' . date('Ymd_His') . '.xlsx';
        return (new FastExcel($exportData))->download($fileName);


    }

    //================RetailerCheckReportView=======================

    public function tsoCheckReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $query = DB::table('tsoupazilas as t1')
            ->select('t3.firstname as dis_name', 't3.officeid as disid', 't2.firstname as tso_name', 't2.officeid as tsoid')
            ->join('users as t2', 't1.user_id', '=', 't2.id')
            ->join('users as t3', 't1.upazila_id', '=', 't3.id')
            ->orderBy('t1.id', 'desc')
            ->get();

        $queryresults = json_decode(json_encode($query), True);

        return view('admin.tsoCheckReport', ['queryresults' => $queryresults]);

    }


    public function srCheckReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $query = DB::table('srs as t1')
            ->select('t2.firstname as dis_name', 't2.officeid as disid', 't3.firstname as sr_name', 't3.officeid as srid')
            ->join('users as t2', 't1.user_id', '=', 't2.id')
            ->join('users as t3', 't1.sr_id', '=', 't3.id')
            ->orderBy('t1.id', 'desc')
            ->get();

        $queryresults = json_decode(json_encode($query), True);


        return view('admin.srCheckReport', ['queryresults' => $queryresults]);

    }


    public function DailyPurchaseSaleReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();


        $distributorResult = User::where('level', 100)->orderBy('id', 'desc')->get();
        $distributors = $distributorResult->toArray();

        $productResult = Product::orderBy('id', 'desc')->get();
        $products = $productResult->toArray();

        $retailerResult = Retailer::orderBy('id', 'desc')->get();
        $retailers = $retailerResult->toArray();

        $ssdata = [];
        $totalamount = [];
        $dailyPurchaseSaleReports = [];
        $ssdata['count'] = 0;

        $purchases = [];
        $sales = [];


        //dd(Session::all());

        $fdate = Session::get('fdate');
        $todate = Session::get('todate');
        $type = Session::get('type');
        $distributor_id = Session::get('distributor_id');


        if ($fdate && $todate && $type && $distributor_id) {
            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['type'] = $type;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------

            if ($distributor_id == "All") {
                //with distributor_id all===========
                if ($type == "Purchase") {
                    $purchases = Purchase::with('product', 'user')->select('id', 'user_id', 'product_id', 'quantity', 'sno', 'status', 'imei', 'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
                } elseif ($type == "Sale") {
                    $sales = Sale::with('product', 'retailer', 'user')->select('id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
                }
                //with distributor_id all===========
            } else {
                //with distributor_id===========
                if ($type == "Purchase") {
                    $purchases = Purchase::with('product', 'user')->select('id', 'user_id', 'product_id', 'quantity', 'sno', 'status', 'imei', 'created_at')->where('user_id', $distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
                } elseif ($type == "Sale") {
                    $sales = Sale::with('product', 'retailer', 'user')->select('id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where('user_id', $distributor_id)->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(500);
                }
                //with distributor_id===========
            }

        }


        //Session::forget(['user_id','fdate','todate']);

        return view('admin.dailyPurchaseSaleReport', ['ssdata' => $ssdata, 'dailyPurchaseSaleReports' => $dailyPurchaseSaleReports, 'distributors' => $distributors, 'sales' => $sales, 'purchases' => $purchases, 'retailers' => $retailers, 'products' => $products]);

    }


    public function DailyPurchaseSaleReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate', 'all_report']);


        $this->validate($request, [
            'fdate' => 'required',
            'todate' => 'required',
            'distributor_id' => 'required',
            'type' => 'required',
        ]);


        $distributor_id = $request->get('distributor_id');
        $type = $request->get('type');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['fdate' => $fdate, 'todate' => $todate, 'type' => $type, 'distributor_id' => $distributor_id]);

        return redirect(route('admin.dailyPurchaseSaleReport'));


    }

    public function PurchaseUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $purchase = Purchase::find($id);

        if ($purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'quantity'=>'required',
                //'product_id'=>'required',
                'distributor_id' => 'required'
            ]);
            //$purchase->quantity = $request->get('quantity');
            //$purchase->product_id = $request->get('product_id');
            $purchase->user_id = $request->get('distributor_id');
            $purchase->save();

            return redirect()->back()->with('success', 'Data has been updated successfully');
        }
    }

    public function PurchaseDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Purchase = Purchase::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Purchase->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }


        }

    }


    public function SaleoutDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Saleout = Smsdetail::find($id);

        $saleoutCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Saleout === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($saleoutCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Saleout->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }


    public function PurchaseInactive(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];

        $purchase = Purchase::find($id);

        if ($purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            $purchase->status = false;
            $purchase->save();

            return redirect()->back()->with('success', 'Data has been inactivated successfully');
        }

    }

    public function PurchaseActive(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('admin.dashboard');
        }

        $this->validate($request, ['id' => 'required']);

        $id = $request['id'];

        $purchase = Purchase::find($id);

        if ($purchase === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            $purchase->status = true;
            $purchase->save();
            return redirect()->back()->with('success', 'Data has been activated successfully');
        }

    }
    public function SaleUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $Sale = Sale::find($id);

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, ['retailer_id' => 'required']);
            $Sale->retailer_id = $request->get('retailer_id');
            $Sale->save();
            return redirect()->back()->with('success', 'Data has been updated successfully');
        }

    }

    public function DsalesDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Sale = Smsdetail::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Sale->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }
    }



    public function SaleDestroy($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Sale = Sale::find($id);

        $productCount = 0;
        //$productCount = Product::where('promo_id', $id)->count();
        //$product = Product::where('promo_id', $id)->get();

        if ($Sale === null) {
            return redirect()->back()->withErrors('There are no data with this id');

        } else {
            if ($productCount > 0) {
                return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
            } else {
                $Sale->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }


        }



    }



    //================DailyPurchaseSaleReport=======================




    //================DailySalesReport=======================


    public function DailyDistributorSalesReportViewPrint($user_id, $fdate, $todate)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        //-------------------------
        if (!$fdate || !$todate || !$user_id) {
            return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
        } else {
            Session::put(['user_id' => $user_id, 'fdate' => $fdate, 'todate' => $todate]);
        }
        //-------------------------

        $user_id = Session::get('user_id');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $totalamount = [];
        $dailyDistributorSalesReports = [];

        if ($user_id) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;


        }




        $pdf = PDF::loadView('distributor.dailySalesReports_print', ['ssdata' => $ssdata, 'dailyDistributorSalesReports' => $dailyDistributorSalesReports, 'totalamount' => $totalamount]);


        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal
        //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal

        return $pdf->stream('userdailySalesReports.pdf');

    }



    public function DailyDistributorSalesReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //Session::forget(['brand_id','user_id','sno','fdate','todate']);

        $brandResult = Brand::select('id', 'name')->orderBy('id', 'desc')->get();
        $brands = $brandResult->toArray();

        //$userResult = User::select('dist_return')->where('level',100)->where('id',Auth::id())->first();
        //$status = $userResult->toArray();



        $distributorResult = User::select('id', 'firstname', 'officeid')->where('level', 100)->orderBy('id', 'desc')->get();
        $distributors = $distributorResult->toArray();


        //$retailerResult = Retailer::select('id as id','name as name','officeid','retailer_id')->where('user_id',Auth::id())->orderBy('id','desc')->get();
        //$retailers = $retailerResult->toArray();


        $distributor_id = Session::get('distributor_id');
        $sno = Session::get('sno');
        $fdate = Session::get('fdate');
        $todate = Session::get('todate');


        /*$sales = Sale::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at')->where('user_id',Auth::id())->paginate(500);
                    //$sales = $saleResult->toArray();*/

        $ssdata = [];
        $totalamount = [];
        $dailyDistributorSalesReports = [];

        if ($distributor_id == 'all' && $fdate && $todate && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['distributor_id'] = $distributor_id;
            $ssdata['sno'] = $sno;


            $dailyDistributorSalesReports = Sale::with('user', 'product', 'retailer', 'sr', 'salereturn')->select('id', 'sr_id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);

        } elseif ($distributor_id != 'all' && $fdate && $todate && !$sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['distributor_id'] = $distributor_id;
            $ssdata['sno'] = $sno;


            $dailyDistributorSalesReports = Sale::with('user', 'product', 'retailer', 'sr', 'salereturn')->select('id', 'sr_id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where(['user_id' => $distributor_id])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


        } elseif ($distributor_id == 'all' && $fdate && $todate && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['distributor_id'] = $distributor_id;
            $ssdata['sno'] = $sno;

            $dailyDistributorSalesReports = Sale::with('user', 'product', 'retailer', 'sr', 'salereturn')->select('id', 'sr_id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where(['sno' => $sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);


        } elseif ($distributor_id != 'all' && $fdate && $todate && $sno) {
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            $ssdata['distributor_id'] = $distributor_id;
            $ssdata['sno'] = $sno;


            $dailyDistributorSalesReports = Sale::with('user', 'product', 'retailer', 'sr', 'salereturn')->select('id', 'sr_id', 'user_id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at')->where(['user_id' => $distributor_id, 'sno' => $sno])->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])->paginate(10000);



        } else {
            $dailyDistributorSalesReports = [];
        }
        //dd($dailyDistributorSalesReports);

        //Session::forget(['retailer_id','user_id','sno','fdate','todate']);

        //dd(Auth::id());


        return view('admin.dailydistributorSalesReport', ['brands' => $brands, 'distributors' => $distributors, 'ssdata' => $ssdata, 'dailyDistributorSalesReports' => $dailyDistributorSalesReports]);

    }


    public function DailyDistributorSalesReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }




        Session::forget(['distributor_id', 'sno', 'fdate', 'todate']);

        $this->validate($request, [
            'distributor_id' => 'required',
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $distributor_id = $request->get('distributor_id');
        $sno = $request->get('sno');
        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['distributor_id' => $distributor_id, 'sno' => $sno, 'fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('admin.dailyDistributorSalesReport'));


    }

    //================DailySalesReport======================



    // Salesrepresentative =======================================

    public function SalesrepresentativeView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();


        //$userResult = User::with('territory')->get();
        $userResult = User::where('level', 50)->orderBy('id', 'desc')->paginate(300);
        //$users = $userResult->toArray();

        //dd($userResult);
//



        return view('admin.salesrepresentative', ['salesrepresentatives' => $userResult]);

    }

    public function SalesrepresentativeViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $statement = DB::select("show table status like 'users'");
        $ainid = $statement[0]->Auto_increment;



        //========================================================================================
        $this->validate($request, [
            //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            'firstname' => 'required|min:2|max:50',
            //'lastname' => 'required|min:1|max:50',
            'email' => 'required|email|unique:users',
            'officeid' => 'required|unique:users',
            'password' => 'required|min:3|max:20',
            'confirm_password' => 'required|min:3|max:20|same:password',
            'contact' => 'required|numeric|min:1',
            //'level' => 'required'
        ]);

        $image = $request->file('image');

        if (!is_null($image)) {
            $this->validate($request, [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]);


            $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
            Storage::put($image_name, file_get_contents($image));
        } else {
            $image_name = NULL;
        }

        $request['remember_token'] = $request['_token'];
        $request['password'] = bcrypt($request['password']);
        $request['photo'] = $image_name;
        //$request['region_id'] = NULL;
        //$request['territory_id'] = NULL;
        $request['level'] = 50;

        User::create($request->all());

        return redirect()->back()->with('success', 'Data has been inserted successfully');

        //========================================================================================



    }

    public function SalesrepresentativeUpdate(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $id = $request->get('id');
        $user = User::find($id);

        if ($user === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            $this->validate($request, [
                //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                'firstname' => 'required|min:2|max:50',
                //'lastname' => 'required|min:1|max:50',
                //'officeid' => 'required|unique:users',
                'contact' => 'required|numeric|min:1',
            ]);


            $image = $request->file('image');
            //$attachment = $request->file('attachment');

            if (!is_null($image)) {

                $this->validate($request, [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200000',
                ]);
                // for deleting file =======================
                File::delete('storage/app/' . $user['photo']);
                // for deleting file =======================

                $image_name = time() . mt_rand() . substr($image->getClientOriginalName(), strripos($image->getClientOriginalName(), '.'));
                Storage::put($image_name, file_get_contents($image));
                //=================================================================
                $user->firstname = $request->get('firstname');
                //$user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                $user->photo = $image_name;
                $user->save();

                //=================================================================

            } else {
                //$image_name = NULL;

                //=================================================================
                $user->firstname = $request->get('firstname');
                //$user->lastname = $request->get('lastname');
                //$user->email = $request->get('email');
                //$user->officeid = $request->get('officeid');
                $user->contact = $request->get('contact');
                //$user->photo = $image_name;
                $user->save();

                //=================================================================

            }

            return redirect()->back()->with('success', 'Data has been updated successfully');

        }


    }





    public function AddSr(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        $this->validate($request, [
            'user_id' => 'required',
            'srs' => 'required',
        ]);

        $user_id = $request->user_id;
        $srs = $request->srs;


        foreach ($srs as $key => $sr) {

            $count = Sr::where(['sr_id' => $sr])->count();

            if ($count > 0) {
                return redirect()->back()->withErrors("Same retailer can not be added")->withInput();
            }


        }




        foreach ($srs as $key => $sr) {

            $user = User::where('id', $sr)->take(1)->first();



            $data['user_id'] = $user_id;
            $data['sr_id'] = $user->id;
            $data['name'] = $user->firstname;
            $data['email'] = $user->email;
            $data['officeid'] = $user->officeid;

            Sr::create($data);


        }

        return redirect()->back()->with('success', 'Data has been inserted successfully');

    }


    public function deleteSr($id)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        if ($id == null) {
            return redirect()->back()->withErrors("Retailer can not be deleted")->withInput();
        }
        $count = Sale::where(['sr_id' => $id])->count();

        if ($count > 0) {
            return redirect()->back()->withErrors('This user can not be deleted due to related to other data');
        }

        DB::table('srs')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully');

    }






    // Salesrepresentative =======================================



    //================DailyRetailerStockReport=======================

    public function DailyRetailerStockReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        return view('admin.dailyRetailerStockReport');

    }

    public function DailyRetailerStockReportViewStore(Request $request)
    {
        //dd($request->all());
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $retailerId = $request->retailer_id;
        $fdate = $request->fdate;
        $todate = $request->todate;

        $products = Product::select('id', 'name', 'model')->get();

        // -------------------------
        // Sale Query
        // -------------------------
        $saleQuery = Sale::select(
            'product_id',
            DB::raw('SUM(quantity) as stockin')
        );
        //dd($saleQuery);

        if ($retailerId) {
            $saleQuery->where('ruser_id', $retailerId);
        }

        if ($fdate && $todate) {
            $saleQuery->whereBetween(DB::raw('DATE(created_at)'), [$fdate, $todate]);
        }

        $sales = $saleQuery
            ->groupBy('product_id')
            ->pluck('stockin', 'product_id');

        // -------------------------
        // Tertiary Query
        // -------------------------
        $tertiaryQuery = Smsdetail::select(
            'product_id',
            DB::raw('COUNT(product_id) as stockout')
        );

        if ($retailerId) {
            $tertiaryQuery->where('user_id', $retailerId);
        }

        if ($fdate && $todate) {
            $tertiaryQuery->whereBetween(DB::raw('DATE(created_at)'), [$fdate, $todate]);
        }

        $tertiaries = $tertiaryQuery
            ->groupBy('product_id')
            ->pluck('stockout', 'product_id');

        // -------------------------
        // Retailer Name
        // -------------------------
        if (!$retailerId) {
            $retailerName = 'All Retailers';
        } else {
            $user = User::find($retailerId);
            $retailerName = $user->firstname . ' - ' . $user->officeid;
        }

        // -------------------------
        // Build Report Array
        // -------------------------
        $reportData = [];

        foreach ($products as $index => $product) {

            $stockIn = $sales[$product->id] ?? 0;
            $stockOut = $tertiaries[$product->id] ?? 0;

            $reportData[] = [
                '#' => $index + 1,
                'Retailer' => $retailerName,
                'Product Name' => $product->name,
                'Product Model' => $product->model,
                'Stock In' => $stockIn,
                'Stock Out' => $stockOut,
                'Stock' => $stockIn - $stockOut,
            ];
        }

        // -------------------------
        // Excel Download
        // -------------------------
        $fileName = 'Retailer_Daily_Stock_Report_' . now()->format('Y_m_d_His') . '.xlsx';

        return (new FastExcel($reportData))->download($fileName);
    }

    //================DailyRetailerStockReport=======================


    //================DailyRTSMSReport======================

    public function DailyRTSMSReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //Session::forget(['fdate','todate']);

        //=====================
        $returnCount = Preturn::where('status', '<=', 2)->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $fdate = Session::get('fdate');
        $todate = Session::get('todate');

        $ssdata = [];
        $preturns = [];

        if ($fdate && $todate) {

            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;


            $preturns = Promortsmsdetail::with('user', 'promort', 'promortdetail')

                ->select('id', 'user_id', 'promort_id', 'promortdetail_id', 'details', 'phoneno', 'created_at', 'updated_at', 'color')
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
                //->OrderBy('status','Desc')
                ->OrderBy('id', 'Desc')
                ->get();
        }

        //dd($preturns);

        return view('admin.dailyRTSMSReport', ['ssdata' => $ssdata, 'preturns' => $preturns]);

    }


    public function DailyRTSMSReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());


        Session::forget(['fdate', 'todate']);

        $this->validate($request, [
            'fdate' => 'required',
            'todate' => 'required'
        ]);


        //dd($request->all());

        $fdate = $request->get('fdate');
        $todate = $request->get('todate');

        Session::put(['fdate' => $fdate, 'todate' => $todate]);


        //dd(Session::all());


        return redirect(route('admin.dailyRTSMSReport'));


    }

    //================DailyRTSMSReport======================


    //================DailyReturnReport======================

    public function DailyReturnReportView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }


        return view('admin.dailyReturnReport');

    }



    public function DailyReturnReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $this->validate($request, [
            'fdate' => 'required|date',
            'todate' => 'required|date',
        ]);

        $fdate = $request->fdate;
        $todate = $request->todate;

        $returnQuery = Preturn::with('user', 'retailer', 'product.brand');

        if ($fdate && $todate) {
            $returnQuery->whereBetween('created_at', [$fdate, $todate]);
        }

        $data = $returnQuery->get();

        $exportData = [];

        foreach ($data as $index => $r) {
            if ($r->status == 1) {
                $statusText = 'ST2 Return Apply';
            } elseif ($r->status == 2) {
                $statusText = 'ST1 Return Apply';
            } elseif ($r->status == 3) {
                $statusText = 'ST1 Return Approved';
            } elseif ($r->status == 4) {
                $statusText = 'ST2 Return Approved';
            } else {
                $statusText = '-';
            }

            $exportData[] = [
                '#' => $index + 1,
                'Status' => $statusText,
                'Distributor Code' => $r->user->officeid ?? '-',
                'Distributor Name' => $r->user->firstname ?? '-',
                'Retailer Code' => $r->retailer->officeid ?? '-',
                'Retailer Name' => $r->retailer->name ?? '-',
                'Product Name' => $r->product->name ?? '-',
                'IMEI-1' => $r->sno ?? '-',
                'IMEI-1' => $r->imei ?? '-',
                'Apply Date' => $r->created_at,
                'Last Action Date' => $r->updated_at,
            ];
        }

        $fileName = 'Daily_Return_Report_' . date('Ymd_His') . '.xlsx';

        return (new FastExcel($exportData))->download($fileName);
    }



    // ReturnProduct =======================================

    public function ReturnProductViewAll()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }



        //=====================
        $returnCount = Preturn::where('status', '<=', 2)->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $preturns = Preturn::with('product')->select('id', 'product_id', 'retailer_id', 'sno', 'imei', 'created_at', 'updated_at', 'status', DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = user_id) as distributor'), DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer'))->OrderBy('status', 'Desc')->OrderBy('id', 'Desc')->paginate(300);


        //dd($preturns);


        return view('admin.returnProductAll', ['preturns' => $preturns]);

    }

    public function ReturnProductView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //=====================
        $returnCount = Preturn::where('status', '<=', 2)->count();
        $_SESSION['returnCount'] = $returnCount;
        //=====================

        $preturns = Preturn::with('product', 'retailer')->select(
            'id',
            'product_id',
            'retailer_id',
            'sno',
            'imei',
            'created_at',
            'updated_at',
            'status',

            DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = user_id) as distributor'),
            DB::raw('(select CONCAT(users.firstname, "-", users.officeid, "-", users.contact) from users where users.id = ruser_id) as retailer')

        )->where('status', '<=', 2)->OrderBy('status', 'Desc')->OrderBy('id', 'Desc')->paginate(200);
        //$sales = $saleResult->toArray();

        return view('admin.returnProduct', ['preturns' => $preturns]);

    }

    public function ReturnProductViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $this->validate($request, ['snos' => 'required']);
        //dd($request->all());
        $snos = array_unique($request->snos);
        $data = [];
        $snoCount = 0;
        foreach ($snos as $key => $value) {
            $sno = $value;
            //=================================
            $count = Preturn::where(['sno' => $sno, 'status' => 2])->count();

            if ($count > 0) {
                DB::table('preturns')->where(['sno' => $sno])->update(['status' => 3, 'nd_id' => Auth::id()]);

                // DB::table('sales')->where(['sno'=>$sno])->delete();
                DB::table('purchases')->where(['sno' => $sno])->delete();

            } else {
                $data[] = $sno;
                $snoCount += 1;
            }
            //=================================

        }

        if ($snoCount > 0) {
            $implode = implode(", ", $data);
            return redirect()->back()->withErrors("$implode s.no has not been update, please check status , others sno has been updated")->withInput();
        }
        return redirect()->back()->with('success', 'Data has been inserted successfully');


    }

    public function ReturnProductDelete($id = null)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $Preturn = Preturn::find($id);

        $status = $Preturn->status;



        if ($Preturn === null) {
            return redirect()->back()->withErrors('There are no data with this id');
        } else {
            if ($status > 2) {
                return redirect()->back()->withErrors('This item can not be deleted becouse of this item already approved');
            } else {
                $Preturn->delete();
                return redirect()->back()->with('success', 'Data has been deleted successfully');
            }
        }

    }



    public function dosView()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }
        //$userCount = User::count();


        $distributorResult = User::where('level', 100)->orderBy('id', 'desc')->get();
        $distributors = $distributorResult->toArray();



        $ssdata = [];
        $totalamount = [];
        $dailyStockReports = [];
        $ssdata['count'] = 0;


        //dd(Session::all());

        $fdate = Session::get('fdate');
        $todate = Session::get('todate');
        $distributor_id = Session::get('distributor_id');
        $all_report = Session::get('all_report');

        if (!$fdate && !$todate && $all_report && $distributor_id) {
            //--------------------------------------------------


            //--------------------------------------------------
            $ssdata['count'] = 1;
            $ssdata['fdate'] = $fdate;
            $ssdata['todate'] = $todate;
            //--------------------------------------------------
            $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();


            foreach ($products as $key => $product1) {
                $product_id = $product1['id'];
                $product = $product1['name'];
                $model = $product1['model'];



                if ($distributor_id == "All") {

                    // with distributor_id========
                    $pcount = Purchase::where('product_id', $product_id)->count();

                    if ($pcount > 0) {
                        $PurchaseResult = Purchase::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('product_id', $product_id)->groupBy('product_id')->first();
                        $Purchases = $PurchaseResult->toArray();

                        //$distributor = $Purchases['user']['firstname'] . " - ". $Purchases['user']['officeid'];
                        $distributor = "All Distributoirs";
                        $sin = $Purchases['sin'];
                    } else {
                        $distributor = "All Distributoirs";
                        $sin = 0;
                    }

                    $scount = Sale::where('product_id', $product_id)->count();

                    if ($scount > 0) {
                        $SaleResult = Sale::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('product_id', $product_id)->groupBy('product_id')->first();
                        $Sales = $SaleResult->toArray();

                        //$distributor = $Sales['user']['firstname'] . " - ". $Sales['user']['officeid'];
                        $distributor = "All Distributoirs";
                        $sout = $Sales['sout'];
                    } else {
                        $distributor = "All Distributoirs";
                        $sout = 0;
                    }


                    $currentDate = date('Y-m-d');
                    $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

                    $scount30days = Sale::where('product_id', $product_id)
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
                        ->count();


                    $dailyStockReports[] = [
                        'distributor' => $distributor,
                        'product_id' => $product_id,
                        'product' => $product,
                        'model' => $model,
                        'stockin' => $sin,
                        'stockout' => $sout,
                        'stock' => $sin - $sout,
                        'lastmonth_sale' => $scount30days
                    ];


                    // with distributor_id========
                } else {

                    // with distributor_id========
                    $pcount = Purchase::where('user_id', $distributor_id)->where('product_id', $product_id)->count();

                    if ($pcount > 0) {
                        $PurchaseResult = Purchase::with('user')->select('user_id', DB::raw('SUM(quantity) AS sin'))->where('user_id', $distributor_id)->where('product_id', $product_id)->groupBy('product_id')->first();
                        $Purchases = $PurchaseResult->toArray();

                        $distributor = $Purchases['user']['firstname'] . " - " . $Purchases['user']['officeid'];
                        $sin = $Purchases['sin'];
                    } else {
                        //$distributor = " - ";
                        $sin = 0;
                        $userData = User::where('id', $distributor_id)->first();
                        $distributor = $userData->firstname . " " . $userData->officeid;
                    }

                    $scount = Sale::where('user_id', $distributor_id)->where('product_id', $product_id)->count();

                    if ($scount > 0) {
                        $SaleResult = Sale::with('user')->select('user_id', DB::raw('COUNT(product_id) as sout'))->where('user_id', $distributor_id)->where('product_id', $product_id)->groupBy('product_id')->first();
                        $Sales = $SaleResult->toArray();

                        $distributor = $Sales['user']['firstname'] . " - " . $Sales['user']['officeid'];
                        $sout = $Sales['sout'];
                    } else {
                        //$distributor = " - ";
                        $sout = 0;
                        $userData = User::where('id', $distributor_id)->first();
                        $distributor = $userData->firstname . " " . $userData->officeid;
                    }

                    // with distributor_id========


                    $currentDate = date('Y-m-d');
                    $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

                    $scount30days = Sale::where('user_id', $distributor_id)
                        ->where('product_id', $product_id)
                        ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
                        ->count();




                    $dailyStockReports[] = [
                        'distributor' => $distributor,
                        'product_id' => $product_id,
                        'product' => $product,
                        'model' => $model,
                        'stockin' => $sin,
                        'stockout' => $sout,
                        'stock' => $sin - $sout,
                        'lastmonth_sale' => $scount30days
                    ];

                }




            }
            //--------------------------------------------------
        }



        //Session::forget(['user_id','fdate','todate']);

        return view('admin.dosReport', ['ssdata' => $ssdata, 'dailyStockReports' => $dailyStockReports, 'distributors' => $distributors]);

    }


    public function dosReportViewStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['fdate', 'todate', 'all_report']);


        if ($request->get('all_report') == null) {

            $this->validate($request, [
                'fdate' => 'required',
                'todate' => 'required',
                'distributor_id' => 'required',
            ]);


            $distributor_id = $request->get('distributor_id');
            $fdate = $request->get('fdate');
            $todate = $request->get('todate');
            //$all_report = $request->get('all_report');

            Session::put(['fdate' => $fdate, 'todate' => $todate, 'distributor_id' => $distributor_id]);
        } else {
            $this->validate($request, [
                'all_report' => 'required',
                'distributor_id' => 'required',
            ]);
            $distributor_id = $request->get('distributor_id');
            $all_report = $request->get('all_report');

            Session::put(['all_report' => $all_report, 'distributor_id' => $distributor_id]);
        }

        return redirect(route('admin.dosReport'));


    }

    public function dosRetailer()
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        $distributorResult = User::where('level', 200)->orderBy('id', 'desc')->get();
        $distributors = $distributorResult->toArray();


        $distributor_id = Session::get('distributor_id');

        $dosReports = [];

        if ($distributor_id == "All") {

            $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();

            foreach ($products as $product1) {
                $product_id = $product1['id'];
                $product = $product1['name'];
                $model = $product1['model'];

                $pcount = Sale::where('product_id', $product_id)->count();

                $distributor = "All Retailer";
                $sin = 0;
                $sout = 0;

                if ($pcount > 0) {
                    $PurchaseResult = Sale::with('user')
                        ->select('user_id', DB::raw('SUM(quantity) AS sin'))
                        ->where('product_id', $product_id)
                        ->groupBy('product_id')
                        ->first();

                    $Purchases = $PurchaseResult->toArray();
                    $sin = $Purchases['sin'];
                }

                $scount = Smsdetail::where('product_id', $product_id)->count();

                if ($scount > 0) {
                    $SaleResult = Smsdetail::with('user')
                        ->select('user_id', DB::raw('COUNT(product_id) as sout'))
                        ->where('product_id', $product_id)
                        ->groupBy('product_id')
                        ->first();

                    $Sales = $SaleResult->toArray();
                    $sout = $Sales['sout'];
                }

                $currentDate = date('Y-m-d');
                $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

                $scount30days = Smsdetail::where('product_id', $product_id)
                    ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
                    ->count();

                $dosReports[] = [
                    'distributor' => $distributor,
                    'product_id' => $product_id,
                    'product' => $product,
                    'model' => $model,
                    'stockin' => $sin,
                    'stockout' => $sout,
                    'stock' => $sin - $sout,
                    'lastmonth_sale' => $scount30days
                ];
            }

        } elseif ($distributor_id) {
            // Handle the case when $distributor_id is not "All"

            $productResult = Product::select('id', 'name', 'model')->orderBy('id', 'desc')->get();
            $products = $productResult->toArray();

            foreach ($products as $product1) {
                $product_id = $product1['id'];
                $product = $product1['name'];
                $model = $product1['model'];

                // Initialize variables for $distributor, $sin, and $sout
                $distributor = "";
                $sin = 0;
                $sout = 0;

                $userData = User::where('id', $distributor_id)->first();

                if ($userData) {
                    $distributor = $userData->firstname . " " . $userData->officeid;
                }

                $pcount = Sale::where('ruser_id', $distributor_id)
                    ->where('product_id', $product_id)
                    ->count();

                if ($pcount > 0) {
                    $PurchaseResult = Sale::with('user')
                        ->select('ruser_id', DB::raw('SUM(quantity) AS sin'))
                        ->where('ruser_id', $distributor_id)
                        ->where('product_id', $product_id)
                        ->groupBy('product_id')
                        ->first();

                    if ($PurchaseResult) {
                        $Purchases = $PurchaseResult->toArray();
                        $sin = $Purchases['sin'];
                    }
                }

                $scount = Smsdetail::where('user_id', $distributor_id)
                    ->where('product_id', $product_id)
                    ->count();

                if ($scount > 0) {
                    $SaleResult = Smsdetail::with('user')
                        ->select('user_id', DB::raw('COUNT(product_id) as sout'))
                        ->where('user_id', $distributor_id)
                        ->where('product_id', $product_id)
                        ->groupBy('product_id')
                        ->first();

                    if ($SaleResult) {
                        $Sales = $SaleResult->toArray();
                        $sout = $Sales['sout'];
                    }
                }

                $currentDate = date('Y-m-d');
                $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

                $scount30days = Smsdetail::where('user_id', $distributor_id)
                    ->where('product_id', $product_id)
                    ->whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$lastMonthDate, $currentDate])
                    ->count();

                $dosReports[] = [
                    'distributor' => $distributor,
                    'product_id' => $product_id,
                    'product' => $product,
                    'model' => $model,
                    'stockin' => $sin,
                    'stockout' => $sout,
                    'stock' => $sin - $sout,
                    'lastmonth_sale' => $scount30days
                ];
            }
        } else {
            $message = "No data found for the selected distributor.";
        }

        return view('admin.dosRetailerReport', compact('distributors', 'dosReports'));
    }


    public function dosRetailerStore(Request $request)
    {
        if (Auth::user()->level != 500) {
            return redirect()->route('logout');
        }

        //dd($request->all());

        Session::forget(['all_report']);


        if ($request->get('all_report') == null) {


            $distributor_id = $request->get('distributor_id');

            //$all_report = $request->get('all_report');

            Session::put(['distributor_id' => $distributor_id]);
        } else {
            $this->validate($request, [
                'all_report' => 'required',
                'distributor_id' => 'required',
            ]);
            $distributor_id = $request->get('distributor_id');
            $all_report = $request->get('all_report');

            Session::put(['all_report' => $all_report, 'distributor_id' => $distributor_id]);
        }

        return redirect(route('admin.retailerDosReport'));


    }

    public function vatReportReady()
    {

        $orders = Order::where('status', 5)
            ->with('user')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vatreports')
                    ->whereRaw('vatreports.order_number = orders.id');
            })
            ->get();

        // Fetch order postings related to fetched orders
        $orderPostings = Ordersposting::whereIn('orader_number', $orders->pluck('id'))->get();

        // Iterate over each order posting
        foreach ($orderPostings as $orderPosting) {
            // Fetch order posting details with related products
            $orderPostingDetails = Orderspostingdetail::where('orderspostings_id', $orderPosting->id)->with('products')->get();

            // Iterate over each order posting detail
            foreach ($orderPostingDetails as $detailInfo) {
                $postingId = $detailInfo->orderspostings_id;
                $productCode = $detailInfo->products->product_code ?? NULL;
                $chalanType = $detailInfo->products->chalan_type ?? NULL;
                $quantity = $detailInfo->quantity;
                $customerCode = $orders->where('id', $orderPosting->orader_number)->pluck('user.officeid')->first();
                $address = $orders->where('id', $orderPosting->orader_number)->pluck('user.address')->first();
                $deliveryInfo = $orderPosting->delivery_info ?? NULL;
                $remarks = $orderPosting->remarks ?? NULL;
                $order_number = $orderPosting->orader_number ?? NULL;
                $issueDate = $orders->where('id', $orderPosting->orader_number)->pluck('created_at')->map(function ($date) {
                    return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('m/d/Y');
                })->first();
                $issueTime = $orders->where('id', $orderPosting->orader_number)->pluck('created_at')->map(function ($time) {
                    return \DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('H:m:s');
                })->first();
                $deliveryDate = $orders->where('id', $orderPosting->orader_number)->pluck('updated_at')->map(function ($date) {
                    return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format('m/d/Y');
                })->first();
                $deliveryTime = $orders->where('id', $orderPosting->orader_number)->pluck('updated_at')->map(function ($time) {
                    return \DateTime::createFromFormat('Y-m-d H:i:s', $time)->format('H:m:s');
                })->first();


                $invoiceDate = $orders->firstWhere('id', $orderPosting->orader_number)->updated_at ?? NULL;
                $invoiceNumber = $postingId . date('dmY', strtotime($invoiceDate));

                $data = [
                    'order_number' => $order_number,
                    'invoice_number' => $invoiceNumber,
                    'chalan_type' => $chalanType,
                    'product_code' => $productCode,
                    'quantity' => $quantity,
                    'customer_code' => $customerCode,
                    'customer_address' => $address,
                    'delivery_info' => $deliveryInfo,
                    'remarks' => $remarks,
                    'issue_date' => $issueDate,
                    'issue_time' => $issueTime,
                    'delivery_date' => $deliveryDate,
                    'delivery_time' => $deliveryTime,

                ];

                Vatreport::create($data);
            }

        }
        return redirect()->back();
    }


    public function memoFunction()
    {
        $uniqueSaleInfo = Sale::select('id', 'memo', 'user_id', 'ruser_id')
            ->groupBy('memo', 'user_id') // Group by memo and user_id
            ->havingRaw('COUNT(*) > 1') // Only select groups with more than 1 row
            ->havingRaw('COUNT(DISTINCT ruser_id) > 1') // Select groups with different ruser_id
            ->orderBy('id', 'asc')
            ->get();

        // Generate the Excel file
        $fileName = 'memo.xlsx';

        return (new FastExcel($uniqueSaleInfo))->download($fileName);
    }


    public function memoUpload(Request $request)
    {
        $path = $request->file('csv_file')->getRealPath();

        $row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);

        $data = array_map('str_getcsv', file($path));
        $csv_data = array_slice($data, 1, count($row_index));

        foreach ($csv_data as $key => $value) {
            $memo = $value[1];
            $user_id = $value[2];
            $ruser_id = $value[3];
            $newMemo = $value[4];
            // dd($user_id);

            Sale::where('memo', $memo)->where('user_id', $user_id)->where('ruser_id', $ruser_id)->update(['memo' => $newMemo]);
        }
    }

    // public function dataSink()
    // {
    //  DB::statement('
    //        DELETE p1
    //        FROM purchases p1
    //        JOIN purchases p2 ON p1.sno = p2.sno AND p1.id > p2.id
    //    ');
    //  return redirect()->back();
    // }
    public function dataSink()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        do {
            $deleted = DB::delete("
            DELETE FROM purchases
            WHERE id IN (
                SELECT id FROM (
                    SELECT p1.id
                    FROM purchases p1
                    JOIN purchases p2
                      ON p1.sno = p2.sno
                     AND p1.id > p2.id
                    LIMIT 10000
                ) t
            )
        ");
        } while ($deleted > 0);

        return redirect()->back()->with('success', 'Duplicates cleaned.');
    }


}
