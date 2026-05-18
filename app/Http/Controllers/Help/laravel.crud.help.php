<?php  
use Redirect;
use Validator;
use Input;
use Session;
use Auth;
use Storage;
use File;
use DB;
use Mail;

use App\User;
use App\Year;
class Crud extends AnotherClass
{
	
	function __construct(argument){}

	// Route for ajax=========
	Route::get('/getCodeOnChangeJouraccountByAjax/{id?}', ['as'=>'ajax.getCodeOnChangeJouraccountByAjax','uses'=>'AjaxController@GetCodeOnChangeJouraccountByAjax'])->where(['id' => '[0-9]+']);


	  public function GetCodeOnChangeJouraccountByAjax($id){
	    //if (Auth::user()->level != 500) { return redirect()->route('logout');}
	   
	    $count = Jouraccount::where(['parent_id'=> $id,'accsetting_id'=> self::$accsetting_id])->count();
	    if ($count > 0) {
	      $jouraccountResult = Jouraccount::where(['parent_id'=> $id,'accsetting_id'=> self::$accsetting_id])->get();
	      return $jouraccount = $jouraccountResult->toArray();
	    }

	  }

	// Route for ajax=========
	


		// Route for brand=========
		  
		  Route::get('/brand', ['as'=>'admin.brand','uses'=>'AdminController@BrandView']);
		  Route::post('/brand', ['as'=>'admin.brand.store','uses'=>'AdminController@BrandViewStore']);
		  Route::put('/brand', ['as'=>'admin.brand.update','uses'=>'AdminController@BrandUpdate']);
			Route::delete('/brand/{id}', ['as'=>'admin.brand.delete','uses'=>'AdminController@BrandDestroy'])->where(['id' => '[0-9]+']);

		// Route for brand=========


// Brand =======================================
  
  public function BrandView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
  	
		$brandCount = Brand::count();
	  
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








}
?>