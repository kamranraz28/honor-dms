<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Requests\ClassName;
//use App\Http\Controllers\AuthController;

use App\Http\Requests\FormWithoutFileData;
use App\Http\Requests\FormWithFileData;

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
use App\Setting;
use App\Brand;
use App\Cat;
use App\Product;
use App\Specification;
use App\Stock;


use App\Promo;
use App\Promodetail;


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


	public function __construct(){
	  $this->middleware('auth')->except(['Test']);
		
		//return Auth::user()->level;

	  //$settingCount = Setting::count();
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



  

	protected function security(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
	}

  public function DashboardView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}

		$_SESSION['favicon'] = self::$favicon;
		$_SESSION['logo'] = self::$logo;


	  return view('admin.dashboard');
	}

	public function Test(){ 
		return "Test Methode";
	}
	// User =======================================
  
  public function UserView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		//$userCount = User::count();
	  
	  //$userResult = User::with('territory')->get();
	  $userResult = User::where('level','!=',200)->orderBy('id','desc')->get();
	  $users = $userResult->toArray();

  	return view('admin.user',['users'=>$users]);

  }

  public function UserViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$statement = DB::select("show table status like 'users'");
		$ainid = $statement[0]->Auto_increment;


		
//========================================================================================
  		$this->validate($request, [
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        'firstname' => 'required|min:2|max:50',
        'lastname' => 'required|min:1|max:50',
        'email' => 'required|email|unique:users',
        'officeid' => 'required|unique:users',
        'password' => 'required|min:3|max:20',
        'confirm_password' => 'required|min:3|max:20|same:password',
        'contact' => 'required|numeric|min:1',
        'level' => 'required'
      ]);	

      	$image = $request->file('image');
				
			  if (!is_null($image)) {
			  	$this->validate($request, [
		          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
		        ]);	


			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
				Storage::put($image_name, file_get_contents($image));
			  }else{
			  	$image_name = NULL;
			  }

			  $request['remember_token'] = $request['_token'];
				$request['password'] = bcrypt($request['password']);
			  $request['photo'] = $image_name;
			  //$request['region_id'] = NULL;
			  //$request['territory_id'] = NULL;
			  //$request['distributor_id'] = NULL;
			  
		  	User::create($request->all());  

			return redirect()->back()->with('success', 'Data has been inserted successfully');

//========================================================================================


 
  }

  public function UserUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		$id = $request->get('id');
  	$user = User::find($id);
		
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request, [
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        'firstname' => 'required|min:2|max:50',
        'lastname' => 'required|min:1|max:50',
        //'officeid' => 'required|unique:users',
        'contact' => 'required|numeric|min:1',
      ]);	


	  	$image = $request->file('image');
			//$attachment = $request->file('attachment');

		  if (!is_null($image)) {

				$this->validate($request, [
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        ]);
// for deleting file =======================
				File::delete('storage/app/' . $user['photo']);
// for deleting file =======================

			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
				Storage::put($image_name, file_get_contents($image));
			//=================================================================
				$user->firstname = $request->get('firstname');
				$user->lastname = $request->get('lastname');
				//$user->email = $request->get('email');
				//$user->officeid = $request->get('officeid');
				$user->contact = $request->get('contact');
				$user->photo = $image_name;
	      $user->save();

			//=================================================================

			  }else{
			  	//$image_name = NULL;

			//=================================================================
				$user->firstname = $request->get('firstname');
				$user->lastname = $request->get('lastname');
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





  public function UserInactive(Request $request){
	  if (Auth::user()->admin == false) { return redirect()->route('user.dashboard');}
  	
  	$this->validate($request,['id'=>'required']);

  	$id = $request['id'];

  	

  	$user = User::find($id);
		
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
			$user->active = false;
  		$user->save();
			return redirect()->back()->with('success', 'Data has been inactivated successfully');
		}

  }

  public function UserActive(Request $request){
	  if (Auth::user()->admin == false) { return redirect()->route('user.dashboard');}
  	
  	$this->validate($request,['id'=>'required']);

  	$id = $request['id'];

  	

  	$user = User::find($id);
		
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
			$user->active = true;
  		$user->save();
			return redirect()->back()->with('success', 'Data has been activated successfully');
		}
	
  }




  public function UpdatePassword(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}	  
		
		//dd($request->all());

		$id = $request->get('id');
  	$user = User::find($id);
		
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request, [
        'password' => 'required|min:3|max:200',
        'confirm_password' => 'required|min:3|max:200|same:password',
      ]);	

			$user->password = bcrypt($request['password']);
      $user->save();

		}

    return redirect()->back()->with('success', 'Password has been updated successfully');

  }



  public function UserDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		$user = User::find($id);
		$distuser = Distuser::where(['user_id'=>$id])->count();
		$invoice = Invoice::where(['user_id'=>$id])->count();
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{

			$photo = $user->photo;

			if (!is_null($photo)) {
				// for deleting file =======================
					File::delete('storage/app/' . $user['photo']);
				// for deleting file =======================
			}

	  	$user->delete();
			return redirect()->back()->with('success', 'Data has been deleted successfully');
		}
		

 
  }


/*

  public function TsoUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}	  
		$distributor_id = $request->get('distributor_id');
		$user_id = $request->get('id');

		foreach ($distributor_id as $key => $value) {
			$distuser = Distuser::where(['id'=>$value,'user_id'=>$user_id])->count();
	  	if ($distuser > 0) {
				return redirect()->back()->withErrors('Distributor is already added, please try again... ');
			}
	  }


	  $this->validate($request,['distributor_id'=>'required','id'=>'required']);
			

    foreach ($distributor_id as $key => $value) {
  		Distuser::create([
        'id' => $value,
        'user_id' => $user_id,
      ]);
  	}

    return redirect()->back()->with('success', 'Data has been added successfully');

  }


  public function TsodistDestroy($did){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		$distuser = Distuser::where(['did'=>$did])->get();

		if ($distuser === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{

	  	distuser::where(['did'=>$did])->delete();
			return redirect()->back()->with('success', 'Data has been deleted successfully');
		}
		

 
  }
*/

// User =======================================





// Setting =======================================
  
  public function SettingView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		//$settingCount = Setting::count();
	  
	  $settingResult = Setting::orderBy('id','desc')->get();
	  $settings = $settingResult->toArray();

  	return view('admin.setting',['settings'=>$settings]);

  }

  public function SettingUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$id = $request->get('id');
  	$setting = Setting::find($id);
		
		if ($setting === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request,['currency'=>'required','code'=>'required','timezone'=>'required','hotline'=>'required','contact'=>'required','vat'=>'required','semail'=>'required']);

//==================================


			$image = $request->file('image');
			//$attachment = $request->file('attachment');

		  if (!is_null($image)) {

				$this->validate($request, [
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        ]);
// for deleting file =======================
				File::delete('storage/app/' . $setting['favicon']);
// for deleting file =======================

			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
				Storage::put($image_name, file_get_contents($image));
			//=================================================================
				$setting->favicon = $image_name;			 

			}

//==================================
//==================================


			$image = $request->file('image1');
			//$attachment = $request->file('attachment');

		  if (!is_null($image)) {

				$this->validate($request, [
          'image1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        ]);
// for deleting file =======================
				File::delete('storage/app/' . $setting['logo']);
// for deleting file =======================

			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
				Storage::put($image_name, file_get_contents($image));
			//=================================================================
				$setting->logo = $image_name;			 

			}

//==================================


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


// Setting =======================================






// Brand =======================================
  
  public function BrandView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		//$brandCount = Brand::count();
	  
	  //$brandResult = Brand::with('territory')->get();
	  $brandResult = Brand::orderBy('id','desc')->get();
	  $brands = $brandResult->toArray();

	 //dd($brands);

  	return view('admin.brand',['brands'=>$brands]);

  }

  public function BrandViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$this->validate($request,['name'=>'required']);

  	Brand::create($request->all());
  	
		return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function BrandUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$id = $request->get('id');
  	$brand = Brand::find($id);
		
		if ($brand === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request,['name'=>'required']);
			$brand->name = $request->get('name');
      $brand->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

 
  }

  public function BrandDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$brand = Brand::find($id);
		
  	$productCount = Product::where('brand_id', $id)->count();
  	//$product = Product::where('brand_id', $id)->get();

		if ($brand === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
  		if ($productCount > 0) {
				return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
			}else{
				$brand->delete();
				return redirect()->back()->with('success', 'Data has been deleted successfully');
			}


		}
		

 
  }


// Brand =======================================



// Cat =======================================
  
  public function CatView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		//$catCount = Cat::count();
	  
	  //$catResult = Cat::with('territory')->get();
	  $catResult = Cat::orderBy('id','desc')->get();
	  $cats = $catResult->toArray();

	 //dd($cats);

  	return view('admin.cat',['cats'=>$cats]);

  }

  public function CatViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$this->validate($request,['name'=>'required']);

  	Cat::create($request->all());
  	
		return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function CatUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$id = $request->get('id');
  	$cat = Cat::find($id);
		
		if ($cat === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request,['name'=>'required']);
			$cat->name = $request->get('name');
      $cat->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

 
  }

  public function CatDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$cat = Cat::find($id);
		
  	$productCount = Product::where('cat_id', $id)->count();
  	//$product = Product::where('cat_id', $id)->get();

		if ($cat === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
  		if ($productCount > 0) {
				return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
			}else{
				$cat->delete();
				return redirect()->back()->with('success', 'Data has been deleted successfully');
			}


		}
		

 
  }


// Cat =======================================



// Product =======================================
  
  public function ProductView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		//$productCount = Product::count();
		$productResult = Product::with('brand','cat')->orderBy('id','desc')->get();
	  $products = $productResult->toArray();

//==================

		$brandResult = Brand::orderBy('id','desc')->get();
	  $brands = $brandResult->toArray();

		$catResult = Cat::orderBy('id','desc')->get();
	  $cats = $catResult->toArray();


//==================

	 //dd($products);

  	return view('admin.product',['products'=>$products,'brands'=>$brands,'cats'=>$cats]);

  }

  public function ProductViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$this->validate($request,[
  		'brand_id'=>'required',
  		'cat_id'=>'required',
  		'name'=>'required',
  	]);

//file upload and update----------------

  $image = $request->file('image');
  if (!is_null($image)) {
    $this->validate($request, [
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
    ]);
    $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
    Storage::put($image_name, file_get_contents($image));
  //=================================================================
    $request['image'] = $image_name;      

  }

//file upload and update----------------

  	Product::create($request->all());
  	
		return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function ProductUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		


  	$id = $request->get('id');
  	$product = Product::find($id);
		
		if ($product === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request,['brand_id'=>'required','cat_id'=>'required','name'=>'required','model'=>'required']);


//file upload and update----------------

  $image = $request->file('image');


  if (!is_null($image)) {
    $this->validate($request, [
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
    ]);
// for deleting file =======================
    File::delete('storage/app/' . $product->image);
// for deleting file =======================
    $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
    Storage::put($image_name, file_get_contents($image));
  //=================================================================
   $product->image = $image_name;    

  }

//file upload and update----------------


			$product->brand_id = $request->get('brand_id');
			$product->cat_id = $request->get('cat_id');
			$product->name = $request->get('name');
			$product->model = $request->get('model');
			$product->details = $request->get('details');
      $product->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

 
  }

  public function ProductDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$product = Product::find($id);
		
  	$productCount = 0;
  	//$productCount = Product::where('product_id', $id)->count();
  	//$product = Product::where('product_id', $id)->get();

		if ($product === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
  		if ($productCount > 0) {
				return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
			}else{
				$product->delete();
				return redirect()->back()->with('success', 'Data has been deleted successfully');
			}


		}
		

 
  }


// Product =======================================





// Specification =======================================
  
  public function SpecificationView(){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    //$specificationCount = Specification::count();
    
    //$specificationResult = Specification::with('territory')->get();
    $specifications = Specification::with('product')->select('id','product_id','name','specificationdetails','created_at')->orderBy('id','desc')->paginate(500);
    //$specifications = $specificationResult->toArray();

		$productResult = Product::orderBy('id','desc')->get();
	  $products = $productResult->toArray();



   //dd($specifications);

    return view('admin.specification',['specifications'=>$specifications,'products'=>$products]);

  }

  public function SpecificationViewStore(Request $request){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    $this->validate($request,['name'=>'required','product_id'=>'required']);

    Specification::create($request->all());
    
    return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function SpecificationUpdate(Request $request){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    $id = $request->get('id');
    $specification = Specification::find($id);
    
    if ($specification === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request,['name'=>'required','product_id'=>'required']);
      $specification->product_id = $request->get('product_id');
      $specification->name = $request->get('name');
      $specification->specificationdetails = $request->get('specificationdetails');
     //$specification->details = $request->get('details');
      $specification->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
    }

 
  }

  public function SpecificationDestroy($id){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    $specification = Specification::find($id);
    
    $productCount = 0;
    //$productCount = Product::where('specification_id', $id)->count();
    //$product = Product::where('specification_id', $id)->get();

    if ($specification === null) {
      return redirect()->back()->withErrors('There are no data with this id');

    }else{
      if ($productCount > 0) {
        return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
      }else{
        $specification->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }
    

 
  }


// Specification =======================================






// Stock =======================================
  
  public function StockView(){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    
    //$stockResult = Stock::with('territory')->get();
    $stocks = Stock::select('id','product_id','imei','sno','wperiod')->with('product')->orderBy('id','desc')->paginate(500);
    //$stocks = $stockResult->toArray();

   	$productResult = Product::orderBy('id','desc')->get();
	  $products = $productResult->toArray();


    return view('admin.stock',['stocks'=>$stocks,'products'=>$products]);

  }

  public function StockViewStore(Request $request){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    $this->validate($request,['product_id'=>'required']);

    $product_id = $request['product_id'];
    $imeis = $request['imeis'];
    $snos = $request['snos'];
    $wperiods = $request['wperiods'];



if ($imeis == null || $snos == null || $wperiods == null) {
	return redirect()->back()->withErrors("Please select add more option")->withInput();
}


foreach ($imeis as $key => $imei) {
	if ($imei == null ) {
		return redirect()->back()->withErrors("Please select IMEI No")->withInput();
	}

	if ($snos[$key] == null ) {
		return redirect()->back()->withErrors("Please select serial No")->withInput();
	}

	if ($wperiods[$key] == null ) {
		return redirect()->back()->withErrors("Please select warranty period")->withInput();
	}



}

foreach ($imeis as $key => $imei) {
	

	$request['product_id'] = $product_id;
	$request['imei'] = $imei;
	$request['sno'] = $snos[$key];
	$request['wperiod'] = $wperiods[$key];
	Stock::create($request->all());

}


//dd(count($imeis));



    
    
    return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function StockUpdate(Request $request){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    $id = $request->get('id');
    $stock = Stock::find($id);
    
    if ($stock === null) {
      return redirect()->back()->withErrors('There are no data with this id');
    }else{
      $this->validate($request,['product_id'=>'required','imei'=>'required','sno'=>'required','wperiod'=>'required']);
      $stock->product_id = $request->get('product_id');
      $stock->imei = $request->get('imei');
      $stock->sno = $request->get('sno');
      $stock->wperiod = $request->get('wperiod');
      $stock->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
    }

 
  }

  public function StockDestroy($id){
    if (Auth::user()->level != 500) { return redirect()->route('logout');}
    
    $stock = Stock::find($id);
    
    $productCount = 0;
    //$productCount = Product::where('stock_id', $id)->count();
    //$product = Product::where('stock_id', $id)->get();

    if ($stock === null) {
      return redirect()->back()->withErrors('There are no data with this id');

    }else{
      if ($productCount > 0) {
        return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
      }else{
        $stock->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully');
      }


    }
    

 
  }


// Stock =======================================





// Promo =======================================
  
  public function PromoView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
	  
	  //$promoResult = Promo::with('territory')->get();
	  $promos = Promo::with('promodetail')->select('id','name','status','sdate as sdate1','edate as edate1','created_at',DB::raw('DATE_FORMAT(sdate,"%D %b %Y") as sdate,DATE_FORMAT(edate,"%D %b %Y") as edate'))->orderBy('id','desc')->paginate(100);
	  //$promos = $promoResult->toArray();


		$productResult = Product::orderBy('id','desc')->get();
	  $products = $productResult->toArray();


	 //dd($promos);

  	return view('admin.promo',['promos'=>$promos,'products'=>$products]);

  }

  public function PromoViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		$this->validate($request,[
			'name'=>'required',
			'sdate'=>'required',
			'edate'=>'required',
			'status'=>'required',
		]);


		$status = $request['status'];


		$products = $request['products'];
    $amounts = $request['amounts'];
    $quantites = $request['quantites'];
    $limits = $request['limits'];
    $details = $request['details'];



		if ($products == null || $amounts == null || $quantites == null || $limits == null || $details == null) {
			return redirect()->back()->withErrors("Please select add more option")->withInput();
		}


		foreach ($products as $key => $product) {
			if ($product == null ) {
				return redirect()->back()->withErrors("Please select product")->withInput();
			}

			if ($amounts[$key] == null ) {
				return redirect()->back()->withErrors("Please select amount")->withInput();
			}

			if ($quantites[$key] == null ) {
				return redirect()->back()->withErrors("Please select quantity")->withInput();
			}

			if ($limits[$key] == null ) {
				return redirect()->back()->withErrors("Please select limit per day")->withInput();
			}

			if ($details[$key] == null ) {
				return redirect()->back()->withErrors("Please select details")->withInput();
			}



		}



		$request['status'] = $status;

		$promo = Promo::create($request->all());

		foreach ($products as $key => $value) {
			$data['promo_id'] = $promo->id;
			$data['product_id'] = $value;
			$data['amount'] = $amounts[$key];
			$data['quantity'] = $quantites[$key];
			$data['limitperday'] = $limits[$key];
			$data['details'] = $details[$key];
			$data['sdate'] = $sdate;
			$data['edate'] = $edate;
			$data['status'] = $status;

			Promodetail::create($data);
		}

		return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }

  public function PromoUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$id = $request->get('id');
  	$promo = Promo::find($id);
		
		if ($promo === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request,['name'=>'required','sdate'=>'required','edate'=>'required']);
			$promo->name = $request->get('name');
			$promo->sdate = $request->get('sdate');
			$promo->edate = $request->get('edate');
//--------------------
			DB::table('promodetails')->where('promo_id', $id)->update(['sdate' => $request->get('sdate'),'edate'=>$request->get('edate')]);
//--------------------

      $promo->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

 
  }

  public function PromoDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$promo = Promo::find($id);
		
  	$productCount = 0;
  	//$productCount = Product::where('promo_id', $id)->count();
  	//$product = Product::where('promo_id', $id)->get();

		if ($promo === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
  		if ($productCount > 0) {
				return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
			}else{
			
//----------------------
				DB::table('promodetails')->where('promo_id', $id)->delete();
//----------------------

				$promo->delete();
				return redirect()->back()->with('success', 'Data has been deleted successfully');
			}


		}
		

 
  }

  public function PromoDetailsDestroy($id){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$promodetail = Promodetail::find($id);
		
  	$productCount = 0;
  	//$productCount = Product::where('promo_id', $id)->count();
  	//$product = Product::where('promo_id', $id)->get();

		if ($promodetail === null) {
			return redirect()->back()->withErrors('There are no data with this id');

		}else{
  		if ($productCount > 0) {
				return redirect()->back()->withErrors('This Data can not be deleted becouse of related with product information');
			}else{
				$promodetail->delete();
				return redirect()->back()->with('success', 'Data has been deleted successfully');
			}


		}
		

 
  }

  public function PromoDetailsUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$id = $request->get('id');
  	//$promodetail = Promodetail::where('id',$id)->count();
  	$promodetail = Promodetail::find($id);
		//dd($promodetail);
  	//dd($request->all());


		if ($promodetail === null) {

			return redirect()->back()->withErrors('There are no data with this id');

		}else{
	  	$this->validate($request,[
	  		'product_id'=>'required',
	  		'amount'=>'required',
	  		'quantity'=>'required',
	  		'limitperday'=>'required'
	  	]);
			$promodetail->product_id = $request->get('product_id');
			$promodetail->amount = $request->get('amount');
			$promodetail->quantity = $request->get('quantity');
			$promodetail->limitperday = $request->get('limitperday');
			$promodetail->details = $request->get('details');
      $promodetail->save();
      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

 
  }



  public function ChangeActiveStatus(Request $request){
		
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$this->validate($request,['id'=>'required','status'=>'required']);

  	$id = $request->get('id');
  	$status = $request->get('status');

  	if ($status == 1) { $status = 0;}else{ $status = 1;}
			$promo = Promo::find($id);
		if ($promo === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$promo->status = $status;
      $promo->save();
//---------------------
	DB::table('promodetails')->where('promo_id', $id)->update(['status' => $status]);

//---------------------

      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

	}


  public function changeActiveStatusPromoDetails(Request $request){
		
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	$this->validate($request,['id'=>'required','status'=>'required']);

  	$id = $request->get('id');
  	$status = $request->get('status');

  	if ($status == 1) { $status = 0;}else{ $status = 1;}
			$promodetail = Promodetail::find($id);
		if ($promodetail === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$promodetail->status = $status;
      $promodetail->save();

      return redirect()->back()->with('success', 'Data has been updated successfully');
		}

	}








// Promo =======================================





// Upload1 =======================================
  
  public function Upload1View(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		

  	return view('admin.upload1');

  }

  public function Upload1ViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
  	//$this->validate($request,['type'=>'required']);

  	$type = $request->type;
//==================================
		$image = $request->file('csv_file');
		
	  if (!is_null($image)) {

			$this->validate($request, [
        'csv_file' => 'required|mimes:csv,txt|max:2096',
      ]);

		 // $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
			//Storage::put($image_name, file_get_contents($image));
		//================================================================= 

		}

//==================================

if ($type == 1) {
// for brand-------------------
//======================
	$count = Brand::count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================
  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));

		foreach ($csv_data as $key => $value) {
			$data['name'] = $value[0];
			Brand::create($data);
		}
// for brand-------------------
} else if($type == 2){
//======================
	$count = Cat::count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================

// for category-------------------

  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));


		foreach ($csv_data as $key => $value) {
			$data['name'] = $value[0];
			Cat::create($data);
		}


// for category-------------------
} else if($type == 3) {
// for product-------------------
//======================
	$count = Product::count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================
  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));
		
		foreach ($csv_data as $key => $value) {
			$product = $value[0];
			$model = $value[1];
			$cat = $value[2];
			$brand = $value[3];
			
			

//---------------------------------------
		$brandResult = DB::table('brands')->select('id','name')->where(['name'=>$brand])->take(1)->first();
		$branddata = json_decode(json_encode($brandResult), True);

		$data['brand_id'] = $branddata['id'];
		
//---------------------------------------
//---------------------------------------
		$catResult = DB::table('cats')->select('id','name')->where(['name'=>$cat])->take(1)->first();
		$catdata = json_decode(json_encode($catResult), True);
		

		$data['cat_id'] = $catdata['id'];
//---------------------------------------
//---------------------------------------
		$data['name'] = $product;
		$data['model'] = $model;
		Product::create($data);
//---------------------------------------

		}
// for product-------------------

}else if($type == 4){

//======================
	$count = Specification::count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================



// for product specification-------------------

  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));
		
		foreach ($csv_data as $key => $value) {
			$name = $value[0];
			$specificationdetails = $value[1];
			$model = $value[2];
			$brand = $value[3];
			
			

//---------------------------------------
		$productResult = DB::table('products')->select('id','name')->where(['model'=>$model])->take(1)->first();
		$productdata = json_decode(json_encode($productResult), True);
		

		$data['product_id'] = $productdata['id'];
//---------------------------------------

//---------------------------------------
		$brandResult = DB::table('brands')->select('id','name')->where(['name'=>$brand])->take(1)->first();
		$branddata = json_decode(json_encode($brandResult), True);

		$data['brand_id'] = $branddata['id'];
		
//---------------------------------------

//---------------------------------------
		$data['name'] = $name;
		$data['specificationdetails'] = $specificationdetails;
		Specification::create($data);
//---------------------------------------

		}
// for product specification-------------------

}else if($type == 5){

//======================
	$count = Stock::count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================


// for product specification-------------------

  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));
		
		foreach ($csv_data as $key => $value) {
			
			$product = $value[0];
//---------------------------------------
		$productResult = DB::table('products')->select('id','name')->where(['name'=>$product])->take(1)->first();
		$productdata = json_decode(json_encode($productResult), True);
		

		$data['product_id'] = $productdata['id'];
//---------------------------------------

			$data['imei'] = $value[1];
			$data['sno'] = $value[3];
			$data['details'] = $value[6];
			$data['wperiod'] = $value[7];
			
			Stock::create($data);
//---------------------------------------

		}
// for product specification-------------------

}else if($type == 6){

//======================
	$count = User::where('id','!=',2)->count();
	if ($count > 0) {
		return redirect()->back()->withErrors("Process can not be completed due to to data has already been stored.")->withInput();
	}
//======================


// for product specification-------------------

  	$path = $request->file('csv_file')->getRealPath();

  	$row_index = file($request->file('csv_file'), FILE_SKIP_EMPTY_LINES);
  	
    $data = array_map('str_getcsv', file($path));
    $csv_data = array_slice($data, 1, count($row_index));
		
		foreach ($csv_data as $key => $value) {
			


			$data['officeid'] = $value[1];
			$data['password'] = Hash::make($value[1]);
			$data['firstname'] = $value[2];
			$data['contact'] = $value[3];
			$data['email'] = $value[4];
			$data['level'] = 200;


			User::create($data);
//---------------------------------------

		}
// for product specification-------------------

}


    //return view('import_fields', compact('csv_data'));


		return redirect()->back()->with('success', 'Data has been inserted successfully');

 
  }


// Upload1 =======================================



// Retailer =======================================
  
  public function RetailerView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		//$userCount = User::count();
	  
	  //$userResult = User::with('territory')->get();
	  $userResult = User::where('level',200)->orderBy('id','desc')->paginate(300);
	  //$users = $userResult->toArray();

//dd($users);


  	return view('admin.retailer',['retailers'=>$userResult]);

  }

  public function RetailerViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	$statement = DB::select("show table status like 'users'");
		$ainid = $statement[0]->Auto_increment;


		
//========================================================================================
  		$this->validate($request, [
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
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
		          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
		        ]);	


			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
				Storage::put($image_name, file_get_contents($image));
			  }else{
			  	$image_name = NULL;
			  }

			  $request['remember_token'] = $request['_token'];
				$request['password'] = bcrypt($request['password']);
			  $request['photo'] = $image_name;
			  //$request['region_id'] = NULL;
			  //$request['territory_id'] = NULL;
			  $request['level'] = 200;
			  
		  	User::create($request->all());  

			return redirect()->back()->with('success', 'Data has been inserted successfully');

//========================================================================================


 
  }

  public function RetailerUpdate(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		$id = $request->get('id');
  	$user = User::find($id);
		
		if ($user === null) {
			return redirect()->back()->withErrors('There are no data with this id');
		}else{
	  	$this->validate($request, [
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        'firstname' => 'required|min:2|max:50',
        //'lastname' => 'required|min:1|max:50',
        //'officeid' => 'required|unique:users',
        'contact' => 'required|numeric|min:1',
      ]);	


	  	$image = $request->file('image');
			//$attachment = $request->file('attachment');

		  if (!is_null($image)) {

				$this->validate($request, [
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2096',
        ]);
// for deleting file =======================
				File::delete('storage/app/' . $user['photo']);
// for deleting file =======================

			  $image_name = time().mt_rand().substr($image->getClientOriginalName(),strripos($image->getClientOriginalName(),'.'));
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

			  }else{
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

// Retailer =======================================




}



