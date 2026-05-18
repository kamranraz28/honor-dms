<?php  

	// reports area===============

		Route::group(['prefix' => 'reports'], function() {
		  
      
      Route::get('/dailySalesReport', ['as'=>'admin.dailySalesReport','uses'=>'AdminController@DailySalesReportView']);
      Route::post('/dailySalesReport', ['as'=>'admin.dailySalesReport.store','uses'=>'AdminController@DailySalesReportViewStore']);
      Route::get('/dailySalesReport/print/{user_id?}/{fdstart?}/{fdend?}', ['as'=>'admin.dailySalesReport.print','uses'=>'AdminController@DailySalesReportViewPrint']);



		});

	// reports area===============








//================DailySalesReport=======================

  
  public function DailySalesReportViewPrint($user_id,$fdate,$todate){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		//-------------------------
  if (!$fdate || !$todate || !$user_id) {
    return redirect()->route('jouraccount.reports.daybook')->withErrors('Date not found, Please select date first');
  }else{
    Session::put(['user_id'=> $user_id,'fdate'=> $fdate,'todate'=>$todate]);
  }
//-------------------------
	  
	  $user_id = Session::get('user_id');
	  $fdate = Session::get('fdate');
	  $todate = Session::get('todate');

		$ssdata = [];
		$totalamount = [];
		$dailySalesReports = [];

if ($user_id) {
	
$ssdata['fdate'] = $fdate;
$ssdata['todate'] = $todate;


}




    $pdf = PDF::loadView('admin.dailySalesReports_print',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);
  
    
    $pdf->setOptions(['isPhpEnabled' => true]); 
    $pdf->setPaper([0, 0, 780, 620], 'landscape'); // $y = 770; $x = 530; for normal 
    //$pdf->setPaper('L', 'landscape'); // $y = 770; $x = 530; for normal 

    return $pdf->stream('userdailySalesReports.pdf');

  }


  
  public function DailySalesReportView(){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}
		$userCount = User::count();
	  
	  $user_id = Session::get('user_id');
	  $fdate = Session::get('fdate');
	  $todate = Session::get('todate');

		$ssdata = [];
		$totalamount = [];
		$dailySalesReports = [];

if ($user_id) {
	
$ssdata['fdate'] = $fdate;
$ssdata['todate'] = $todate;
$ssdata['user_id'] = $user_id;


}




//Session::forget(['user_id','fdate','todate']);

  	return view('admin.dailySalesReport',['ssdata'=>$ssdata,'dailySalesReports'=>$dailySalesReports,'totalamount'=>$totalamount]);

  }


  public function DailySalesReportViewStore(Request $request){
		if (Auth::user()->level != 500) { return redirect()->route('logout');}

    //dd($request->all());


    Session::forget(['user_id','fdate','todate']);

    $this->validate($request, [
      'user_id' => 'required',
      'fdate' => 'required',
      'todate' => 'required'
    ]);


    //dd($request->all());

    $user_id = $request->get('user_id');
    $fdate = $request->get('fdate');
    $todate = $request->get('todate');
    
    Session::put(['user_id'=>$user_id,'fdate'=>$fdate,'todate'=>$todate]);

  return redirect(route('admin.dailySalesReport'));


  }

//================DailySalesReport=======================

?>