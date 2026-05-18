<?php  

INSERT INTO `smsdetails` (`id`, `product_id`, `brand_id`, `promo_id`, `promodetail_id`, `user_id`, `mobile`, `imei`, `sno`, `quantity`, `wperiod`, `status`, `iswar`, `remarks`, `created_at`, `updated_at`) VALUES
(16041, 1, 5, 0, 0, 5891, '8801969002208', '', '359517092842830', 1, 365, 0, 1, NULL, '2020-02-01 23:28:43', '2020-02-01 23:28:43');


SELECT SUM(debit) as debit, SUM(credit) as credit FROM `jourposts` WHERE jouraccount_id = 69 AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '2019-06-23' AND '2019-06-23'


SELECT SUM(tdebit) FROM `journals` WHERE id IN (SELECT journal_id FROM `jourposts` WHERE jouraccount_id = 69 AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '2019-06-23' AND '2019-06-23')


SELECT * FROM table WHERE id = 69 AND DATE_FORMAT(created_at,'%Y-%m-%d') BETWEEN '2019-06-23' AND '2019-06-23'


SELECT * FROM `jourposts` WHERE jouraccount_id = 69 AND DATE_FORMAT(vdate,'%Y-%m-%d') BETWEEN '2019-06-23' AND '2019-06-23'



//==========
update sales set sales.brand_id = (select products.brand_id from products where products.id = sales.product_id)

update promodetails set promodetails.brand_id = (select products.brand_id from products where products.id = promodetails.product_id)

update purchases set purchases.brand_id = (select products.brand_id from products where products.id = purchases.product_id)

update stocks set stocks.brand_id = (select products.brand_id from products where products.id = stocks.product_id)

update sales set sales.ruser_id = (select retailers.retailer_id from retailers where retailers.id = sales.retailer_id)

//===============


15390891691204189819.png
OB49BtKhTdHnNbhVxA1mSt7L5fOaBhWadUpKhK0koBwZURcP8cRcq9bzDuhr
$2y$10$oW5KiEipJ8GKM0iYnq3oH.ujOSjmozvEOs9KGLXFkFTTYDM0uG81m

  $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $link = $_SERVER['REQUEST_URI'];

$statement = DB::select("show table status like 'journals'");
$journal_id = $statement[0]->Auto_increment;
$drno =  $journal_id + date('d') +  date('m') + date('Y');

$str = "Mobinur Rahman-2046"; $pos = strripos($str,"-"); $str = substr($str,$pos+1);

date_format(date_create($element->date),"d-M-Y")

$bdate = date('Y-m-d', strtotime('-1 day', strtotime($fdate)));

$date1 = strtotime(date_format(date_create("2016/01/25 00:00:00"),"Y-m-d"));
$date2 = strtotime(date_format(date_create("2016/01/30 00:00:00"),"Y-m-d"));
$seconds_diff = ($date2 - $date1)/86400;
exit;

$fltnumber = (float) $str;

$json = json_decode($string);
echo json_encode($json, JSON_PRETTY_PRINT);


CREATE INDEX accsetting_id ON jouraccounts (accsetting_id)

<a href="{{ route('admin.user.deleteRetailer',$retailer['id']) }}" onclick="return confirm('Do you want to delete this data ?')" class="btn btn-xs btn-danger" >
          <i class="fa fa-trash-o" aria-hidden="true"></i>
        </a>
        
<div class="table-responsive" 
  style="overflow-x: scroll;overflow-y: scroll; height: 300px;white-space:nowrap; width:100%">
</div>

//SELECT t1.id, (CASE WHEN t1.hvat = 5 THEN "good" END) AS hvat FROM hoslabinvoices AS t1
//SELECT t1.id, (CASE WHEN t1.hvat = 6 THEN "good" ELSE "bad" END) AS hvat FROM hoslabinvoices AS t1
//SELECT t1.id, (CASE WHEN t1.total = t1.payment THEN "Paid" ELSE "Unpaid" END) AS paymentstatus FROM hoslabinvoices AS t1
//SELECT t1.id, (CASE WHEN t1.total = t1.payment THEN "Paid" ELSE "Unpaid" END) AS paymentstatus, DATE_FORMAT(t1.created_at,"%D %b %y") as date FROM hoslabinvoices AS t1


//Dublicate id serach
//"SELECT invproduct_id, COUNT(sno) FROM invprocodes GROUP BY invproduct_id"
//SELECT sno FROM sales GROUP BY sno HAVING COUNT(sno) > 1
//SELECT id, sno FROM `sales` WHERE sno IN (SELECT sno FROM sales GROUP BY sno HAVING COUNT(sno) > 1)

// SELECT DISTINCT id FROM `sales` WHERE sno IN (SELECT sno FROM sales GROUP BY sno HAVING COUNT(sno) > 1) GROUP BY sno


DB::table('users')->where('id', 1)->update(['votes' => 1]);

DB::table('hoslabinvoices')->where('id', $id)->delete();
DB::table('hoslabinvoicedetails')->where('hoslabinvoice_id', $id)->delete();



  //$dmsl = date('Y-m-d',strtotime(date("Y-m-d") . "- 1 month"));         
  //$dmsl1 = date('Y-m-d',strtotime(date("Y-m-d") . "-1 week"));    

  $dwsl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 week")),date("Y-m-d")])
                ->count();

  $dmsl = Sale::where(['product_id'=>$product_id,'brand_id'=>2])
                ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [date('Y-m-d',strtotime(date("Y-m-d") . "-1 month")),date("Y-m-d")])
                ->count();


//-----------------
$query = Sale::where(['sno'=>'869545049264470','ruser_id'=>Auth::id()])->first();
$sales = $query->toArray();

$query = DB::table('hospatients')
  ->select('id')
  ->where(['user_id'=>Auth::id()])
  ->orderBy('id','desc')
  ->take(1)
  ->first();
$queryresults = json_decode(json_encode($query), True);

$hospatient_id = $queryresults['id'];



$productResult = Product::with('productCategory')->orderBy('id','desc')->get();
$products = $productResult->toArray();


$preturns = Preturn::with('product','retailer')->select('id','product_id','retailer_id','sno','imei','created_at','updated_at','status',DB::raw('(select CONCAT(users.firstname, "-", users.officeid) from users where users.id = user_id) as distributor'))->OrderBy('id','Desc')->paginate(300);
//$sales = $saleResult->toArray();

$userResult = User::with('division','district','upazila')->select('id','firstname','lastname','contact','level','created_at','updated_at','status','active','photo','email',
  'division_id','district_id','upazila_id','officeid',
    DB::raw('(select CONCAT(t1.firstname, "-", t1.officeid) from users as t1 where t1.id = users.tso_id) as tso'))->where(['active'=> 0,'status'=> 0,'level'=>200])->orderBy('id','desc')->paginate(300);




$data = DB::table('hospatients as t1')
          ->select(DB::raw('CONCAT(t1.name, "-", t1.patientid) as name'))
          ->where("name","LIKE","%{$request->input('query')}%")
          ->get();
return response()->json($data);

$query = DB::table('hoslabinvoices')
  ->select('id','vno')
  ->where(['accsetting_id'=>self::$accsetting_id])
  ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
  ->orderBy('id','desc')
  ->get();
$queryresults = json_decode(json_encode($query), True);


$query = DB::table('hoslabinvoices as t1')
  ->select(DB::raw('(CASE WHEN t1.total = t1.payment THEN "Paid" ELSE "Due" END) AS paymentstatus, DATE_FORMAT(t1.created_at,"%D %b %y") as date'),
    't1.id','t1.vno','t1.hvat','t1.hdiscount','t1.total','t1.payment','t1.due','t1.approve',
    't2.name as doctor',
    't3.name as patient','t3.patientid as patientid')
  ->join('hosdoctors as t2', 't1.hosdoctor_id', '=', 't2.id')
  ->join('hospatients as t3', 't1.hospatient_id', '=', 't3.id')
  ->where(['t1.accsetting_id'=>self::$accsetting_id])
  ->orderBy('t1.id','desc')
  ->get();
  //->paginate(5)
$queryresults = json_decode(json_encode($query), True);


$query = Smsdetail::with('product')->select('id','product_id','promo_id','promodetail_id','sno','imei','wperiod',
          DB::raw('DATEDIFF(NOW(),created_at) as wdayCount, DATE_FORMAT(created_at,"%m/%d/%Y") as saledate,
            DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
            DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'))

                  ->where(['imei' => $imei])
                  ->orWhere(['sno'=>$imei])
                  //->take(1)
                  ->get();
$data = json_decode(json_encode($query), True); 





$query = DB::table('journals as t1')
  ->select(DB::raw('(CASE WHEN t1.vtype = 1 THEN "Payment" WHEN t1.vtype = 2 THEN "Receipt"  WHEN t1.vtype = 3 THEN "Journal" ELSE "Contra" END) AS vtype, (CASE WHEN t1.rpmode = 1 THEN "Cash" WHEN t1.rpmode = 2 THEN "Bank" WHEN t1.rpmode = 3 THEN "Cash & Bank" ELSE "Others" END) AS rpmode, DATE_FORMAT(t1.created_at,"%D %b %y") as date'),DB::raw('DATEDIFF(NOW(),created_at) as wdayCount'),
    't1.id','t1.vno','t1.tdebit','t1.tcredit','t1.narration')
  ->where(['accsetting_id'=>self::$accsetting_id])
  ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
  ->orderBy('id','desc')
  ->get();
$level1datas = json_decode(json_encode($query), True);



$query = DB::table('jourposts as t1')
  ->select(DB::raw('SUM(t1.debit - t1.credit) as clbalance'),'t1.id as jourpost_id',
    DB::raw('(SELECT SUM(st1.debit - st1.credit) FROM jourposts as st1 
      WHERE st1.id < t1.id AND st1.rpmode != 1) as opnbalance'))
  ->where('t1.rpmode', '!=',1)
  ->whereBetween(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), [$fdate, $todate])
  //->groupby('t1.jouraccount_id')
  ->orderBy('t1.id','asc')
  ->first();
$level1datas['balance'] = json_decode(json_encode($query), True);


$query = DB::table('targets as t1')
  ->select(DB::raw('SUM(t1.subtotal) as remainingvalue'),
    't1.product_id as tar_product_id',
    DB::raw('(SELECT FORMAT(SUM(t2.total_delivery_amount),0) FROM dreportdetails as t2 
          WHERE t2.product_id = t1.product_id AND DATE_FORMAT(date,"%Y-%m") = t1.date ) as tdeliveryboxvalue')
    )
  ->where(['t1.product_id'=>$product_id])
  ->where('t1.date', $dateMonth)
  ->first();
$tarachremainingvalue = json_decode(json_encode($query), True);
$section1['tarachremainingvalue'][] = $tarachremainingvalue;


$query = DB::table('dreportdetails as t1')
  ->select(DB::raw('SUM(t1.pro_order) as gtorder, SUM(t1.pro_delivery) as gtdelivery, FORMAT(SUM(t1.total_order_amount),1) as gtotalOrderValue, FORMAT(SUM(t1.total_delivery_amount),1) as gtotalDeliveryValue, SUM(t1.total_delivery_amount) as gtotalDeliveryValue1')
    ,'t1.id as dreportdetail_id','t1.product_id as product_id','t1.product as product','t1.sku as sku',
    
    DB::raw('(SELECT(CASE WHEN SUM(t2.ordered) IS NULL THEN 1 ELSE SUM(t2.ordered) END) FROM dreports as t2 
              WHERE DATE_FORMAT(date,"%Y-%m-%d") = t1.date )  as noordered1')
  )
  //->where(['status'=>1,'distributor_id'=>$distributor_id])
  ->where(DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"), date('Y-m-d', $time))
  //->groupby('distributor_id')
  ->first();
$dreportdetailresult = json_decode(json_encode($query), True);

//-----------------

//variable passing in subquery
$query = DB::table('targets as t1')
  ->select(DB::raw('SUM(t1.product_qty) as totalbox'),
    DB::raw("(SELECT SUM(t2.pro_delivery) FROM dreportdetails as t2 
          WHERE DATE_FORMAT(date,'%Y-%m') = '$dateMonth' ) as tdeliverybox")
    )
  //->where(['t1.product_id'=>$product_id])
  ->where('t1.date', $dateMonth)
  ->first();
$tarachremainingbox = json_decode(json_encode($query), True);

//------------------------------------

//------------------------------------

$query = DB::table('dreportdetails as t1')
  ->select(DB::raw('SUM(t1.pro_order) as gtorder, SUM(t1.pro_delivery) as gtdelivery, FORMAT(SUM(t1.total_order_amount),1) as gtotalOrderValue, FORMAT(SUM(t1.total_delivery_amount),1) as gtotalDeliveryValue, SUM(t1.total_delivery_amount) as gtotalDeliveryValue1,FORMAT((SUM(t1.pro_delivery)/SUM(t1.pro_order))*100, 0) as deliveryrate')
    ,'t1.id as dreportdetail_id','t1.product_id as product_id','t1.product as product','t1.sku as sku',
    
    DB::raw('(SELECT(CASE WHEN SUM(t2.ordered) IS NULL THEN 1 ELSE SUM(t2.ordered) END) FROM dreports as t2 
              WHERE DATE_FORMAT(date,"%Y-%m-%d") = t1.date )  as noordered1')
  )
  //->where(['status'=>1,'distributor_id'=>$distributor_id])
  ->where(DB::raw("DATE_FORMAT(date,'%Y-%m-%d')"), date('Y-m-d', $time))
  //->groupby('distributor_id')
  ->first();
$dreportdetailresult = json_decode(json_encode($query), True);

//------------------------------------
  
$query = DB::table('journals as t1')
  ->select(
    't1.vdate','t1.vno','t1.id',
    't2.jouraccount_id',
    DB::raw('(CASE WHEN t1.vtype = 1 THEN "Payment" WHEN t1.vtype = 2 THEN "Receipt"  WHEN t1.vtype = 3 THEN "Journal" ELSE "Contra" END) AS vtype, (CASE WHEN t1.rpmode = 1 THEN "Cash" WHEN t1.rpmode = 2 THEN "Bank" WHEN t1.rpmode = 3 THEN "Cash" ELSE "Others" END) AS rpmode, DATE_FORMAT(t1.vdate,"%D %b %y") as date'),
    DB::raw('(CASE WHEN t2.gid1 = 1 THEN SUM(t2.debit) WHEN t2.gid1 = 2 THEN SUM(t2.credit) WHEN t2.gid1 = 3 THEN SUM(t2.credit) ELSE SUM(t2.debit) END) AS balance'),

    DB::raw('(CASE WHEN t1.rpmode = 1 THEN 
      (SELECT name FROM jourposts WHERE journal_id = t1.id AND rpmode = 1 LIMIT 1)
       WHEN t1.rpmode = 2 THEN 
       (SELECT name FROM jourposts WHERE journal_id = t1.id AND rpmode = 2 LIMIT 1) 
       WHEN t1.rpmode = 3 THEN 
       (SELECT name FROM jourposts WHERE journal_id = t1.id AND rpmode = 1 LIMIT 1) ELSE "Others" END) AS name'
     )
  )
  ->join('jourposts as t2','t2.journal_id','=','t1.id')
  ->where(['t1.accsetting_id'=>self::$accsetting_id, 't2.jouraccount_id' => $ledger])
  ->whereBetween(DB::raw("DATE_FORMAT(t1.vdate,'%Y-%m-%d')"), [$fdate, $todate])
  ->groupby('t2.vdate')
  ->orderBy('t1.id','asc')
  ->get();

$ledgerbookdatas = json_decode(json_encode($query), True);





//---------------------------------
$query = DB::table('jourposts')
  ->select('id','vdate')
  ->where(['accsetting_id'=>self::$accsetting_id])
  ->orderBy('id','asc')
  ->first();
$queryresults = json_decode(json_encode($query), True);
$ind  = $queryresults['vdate'];
//---------------------------------

$query = DB::table('jourposts as t1')
  ->select(DB::raw('SUM(t1.debit) as totalcredit, SUM(t1.credit) as totaldebit, SUM(t1.debit - t1.credit) as clbalance'),
    DB::raw("(SELECT SUM(st1.debit - st1.credit) FROM jourposts as st1 
      WHERE t1.accsetting_id = st1.accsetting_id AND st1.vdate BETWEEN '$ind' AND  DATE_SUB('$fdate', INTERVAL 1 DAY) AND st1.rpmode != 2) as opnbalance"))
  ->where('t1.rpmode', '!=',2)
  ->where(['t1.accsetting_id'=>self::$accsetting_id])
  ->whereBetween(DB::raw("DATE_FORMAT(vdate,'%Y-%m-%d')"), [$fdate, $todate])
  //->groupby('t1.jouraccount_id')
  ->orderBy('t1.id','asc')
  ->first();
$bankbookdatas['balance'] = json_decode(json_encode($query), True);





//--------------------------------------------------

  # step 1   
    $query = DB::table('products as t1')
             ->select('t1.id','t1.name','t1.model',
              DB::raw('SUM(t2.quantity) as quantity')
           )
             ->join('purchases as t2', 't2.product_id', '=', 't1.id')
             ->groupBy('t2.product_id')
             ->get();

    $products = json_decode(json_encode($query), True);

  # step 2  
    $query = DB::table('products as t1')
             ->select('t1.id','t1.name','t1.model',

              DB::raw('(SELECT(CASE WHEN SUM(t2.quantity) IS NULL THEN 0 ELSE SUM(t2.quantity) END) FROM purchases as t2 
              WHERE t2.product_id = t1.id )  as quantity'))
             ->get();


    $products = json_decode(json_encode($query), True);

  # step 3  
    $query = Product::select('name','id','model')->get();
    $products = $query->toArray();


    foreach ($products as $key => $product) {
      $product_id = $product['id'];
      $productname = $product['name'];
      $productmodel = $product['model'];

      //dd($productmodel);


      $query = Purchase::select(DB::raw('(CASE WHEN SUM(quantity) IS NULL THEN 0 ELSE SUM(quantity) END) as quantity'))
            ->where(['product_id'=>$product_id])
            ->first();
      $purchase = $query->toArray();

      $purchases[] = [
        'product'=>$productname,
        'model'=>$productmodel,
        'quantity'=>$purchase['quantity'],
      ];


    }
dd($purchases);
//--------------------------------------------------






DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ewarranty
DB_USERNAME=micromax
DB_PASSWORD=955945abc




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















$d=cal_days_in_month(CAL_GREGORIAN,2,1965);
$d=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));

  $datalist = [];
  for($d=1; $d<=31; $d++)
  {
    $time=mktime(12, 0, 0, date('m'), $d, date('Y'));          
    if (date('m', $time)==date('m')) {
      $c_day = date('D', $time);

      if ($c_day == "Fri") {
        $datalist[] = date('l', $time);
      } else {
        $datalist[] = date('Y-m-d-D', $time);
      }
    }      
          
  }
  dd($datalist);
//------------------------------------

?>




<table>
  
  <tbody>
      <tr>
        <td colspan="11">
          {{ $hospatients->links() }}
        </td>
      </tr>
  </tbody>

</table>


<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%"> </div>


