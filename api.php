<?php
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


require_once('db/StaticConnection.php');

class EWarrantyXtra extends StaticConnection{
	
	//$url = http://localhost:8012/php/ewarrantyapi/route.php?text=KeyWord SR30212533 DHK001&mobileno=01712616057

	public static function get_data($smsBody = null, $msisdn =null){

		//return " Text : ". $text . " Mobile No : " . $mobileno;
		
		//return $msisdn;



		$mbno= substr($msisdn, 0, 5);
		if($mbno=='88019'){
			$msisdn=$msisdn;
		}
		elseif($mbno=='88016'){
			$msisdn=$msisdn;
		}
		elseif($mbno=='88018'){$msisdn=$msisdn;}
		elseif($mbno=='88015'){$msisdn=$msisdn;}
		elseif($mbno=='88013'){$msisdn=$msisdn;}
		elseif($mbno=='88014'){$msisdn=$msisdn;}
		elseif($mbno=='88017'){$msisdn=$msisdn;}
		else{

/*$msisdn=$msisdn;
$postdata = http_build_query(
    array(
        'v_msisdn' => $msisdn
       )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);*/

//$context  = stream_context_create($opts);
/*$msisdn1 = file_get_contents('http://103.36.103.27/getRealMsisdnRaw.php?v_msisdn='.$msisdn);*/
 	/*$decode= json_decode($msisdn1,  true);
 	$msisdn= $decode['msisdn'];*/

 	$msisdn=$msisdn;

 	 //$msisdn = file_get_contents('http://103.36.103.27/getRealMsisdnRaw.php?v_msisdn='.$msisdn, false);

		}

		$statement = parent::$db->prepare("INSERT INTO smslog (phone_no,sms) VALUES(?,?)"); 
		$statement->execute(array($msisdn,$smsBody));


		$exp_text = explode(" ",$smsBody);

		$keyword = strtoupper($exp_text[0]);
		$sno = $exp_text[1];

		@$retailid1 = $exp_text[2];
		@$retailid=htmlspecialchars_decode($retailid1, ENT_COMPAT); 

		@$color = strtoupper($exp_text[3]);


		if (!@$color) {
			$color = null;
		}
		if (!@$retailid) {
			$retailid = 'XTRAONL';
		}




//--------------------------------

		$statement = parent::$db->prepare("SELECT id, contact FROM users where officeid = ? AND active='1'");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$retailid]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);
		if($count>0){

		@$user_id = $result->id;
		@$retail_contact = $result->contact;
//--------------------------------



if($keyword == "XTRA" || $keyword == "MOTO" || $keyword == "SOF" || $keyword == "HWE" || $keyword == "LNV" || $keyword == "DIZO"){
			//return "Valid";
//-------------------
if($keyword == "XTRA"){$msg="Salextra Fan";
$company="Salextra";
$comp=$company;}
elseif($keyword == "MOTO"){$msg="Motorola Fan";
$company="Motorola";
$comp=$company;}
elseif($keyword == "SOF"){$msg="Sofel Fan";
$company="Sofel";
$comp=$company;}
elseif($keyword == "HWE"){$msg="Huawei Fan";
$company="Huawei";
$comp=$company;}
elseif($keyword == "LNV"){$msg="Lenovo Fan";
$company="Lenovo";
$comp=$company;}
elseif($keyword == "DIZO"){$msg="DIZO Fan";
$company="DIZO";
$comp=$company;}

		$statement = parent::$db->prepare("SELECT id, product_id, brand_id, imei, wperiod FROM stocks where sno = ? OR imei = ?  ");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$sno,$sno]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$product_id = $result->product_id;
		@$imei = $result->imei;
		@$brand_id = $result->brand_id;




		//For Motorola Promotion

$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$results = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $results->name;
		

if($brandname=="Motorola"){
@$wperiod = $result->wperiod;
/*@$wp = $result->wperiod;
@$wperiod=$wp+50;*/
}
else{
@$wperiod = $result->wperiod;
}

//End Motorola Promotion

//-------------------
		if ($count > 0) {

			$statement = parent::$db->prepare("SELECT id FROM smsdetails where sno = ? or imei= ?");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$sno,$sno]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		//$result = $statement->fetch(PDO::FETCH_OBJ);
//-------------------
			if($count > 0){
				$response=  array("status"=>"4000","response"=>"warranty is already Activated!! For Purchasing More Product visit our website https://salextra.com.bd");
			echo json_encode($response);

			exit();

			}
			$statement = parent::$db->prepare("SELECT id FROM smsdetails where sno = ? or imei= ?");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$imei,$imei]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		//$result = $statement->fetch(PDO::FETCH_OBJ);
//-------------------
			if($count > 0){
				$response=  array("status"=>"4000","response"=>"warranty is already Activated!! For Purchasing More Product visit our website https://salextra.com.bd");
			echo json_encode($response);

			exit();

			}
			
//-------------------
		$statement = parent::$db->prepare("SELECT id FROM smsdetails where sno = ? or imei= ?");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$imei,$imei]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		//$result = $statement->fetch(PDO::FETCH_OBJ);
//-------------------
			if($count < 1 ){
//-------------------
		
//-------------------
		$statement = parent::$db->prepare("SELECT id FROM promodetails WHERE product_id = ? AND status = ? AND CURDATE() BETWEEN sdate AND edate");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$product_id,1]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		//$result = $statement->fetchall(PDO::FETCH_OBJ);
//-------------------



if ($count > 0 && $retailid!="daraz" && $retailid!="DARAZ" && $retailid!="Daraz" && $retailid!="XTRA" && $retailid!="xtra" && $retailid!="Xtra" ) {


	
//-------------------
		

//  ============= updated code ===================
	self::check_limit_qty($product_id);
//  ============= updated code ===================


	//-------------------
		$statement = parent::$db->prepare("SELECT id, limitperday,quantity,promo_id,details FROM promodetails WHERE product_id = ? AND status = ? AND status1 = ? AND CURDATE() BETWEEN sdate AND edate");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$product_id,1,1]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);
		//$frresult = $statement->fetch(PDO::FETCH_OBJ);

		@$id = $result->id;
		@$id1 = $result->id;
		@$promo_id = $result->promo_id;
		@$limitperday = $result->limitperday;
		@$quantity = $result->quantity;
		@$details = $result->details;


		$statement = parent::$db->prepare("SELECT name FROM promos WHERE id = ?");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$promo_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);
		//$frresult = $statement->fetch(PDO::FETCH_OBJ);
		@$promo_name = $result->name;


//-------------------
//-------------------
		$statement = parent::$db->prepare("SELECT id FROM smsdetails where promodetail_id = ? AND DATE_FORMAT(created_at,'%Y-%m-%d') = CURDATE()");
		$statement->execute([$id1]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
//-------------------
if ($count >= $limitperday) {
	
	
	//-------------------------------------------
		$statement = parent::$db->prepare("SELECT brand_id,name,cat_id FROM products WHERE id = ?");
		$statement->execute([$product_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brand_id = $result->brand_id;
		@$product_name = $result->name;
		@$catid = $result->cat_id;
//-------------------------------------------
//
	$statement = parent::$db->prepare("SELECT name FROM cats WHERE id = ?");
		$statement->execute([$catid]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$catname = $result->name;

		$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $result->name;

	//---------------------------------
		$statement = parent::$db->prepare("INSERT INTO smsdetails (product_id,promo_id,promodetail_id,user_id,mobile,imei,sno,wperiod,brand_id,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,NOW(),NOW())"); 
		$statement->execute(array($product_id,0,0,$user_id,$msisdn,$imei,$sno,$wperiod,$brand_id));
//---------------------------------

$wper=$wperiod."days";
$date = date('Y-m-d', strtotime($wper));
if($brandname=="Motorola"){

 $response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");
}
else{
$response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");
}
			echo json_encode($response);
			//return "Thank you for purchasing $product_name, your warrnaty is activated";
			
			//self::get_error_msg($mobileno,$msg="Thank you for purchesing $product_name, your warrnaty is activated");
} else {
	

//-------------------
		$statement = parent::$db->prepare("SELECT id FROM smsdetails where promodetail_id = ?");
		$statement->execute([$id1]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
//-------------------

if ($count >= $quantity) {
	
	//-------------------------------------------
		$statement = parent::$db->prepare("SELECT brand_id,name,cat_id FROM products WHERE id = ?");
		$statement->execute([$product_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brand_id = $result->brand_id;
		@$product_name = $result->name;
		@$catid = $result->cat_id;
//-------------------------------------------
//
	$statement = parent::$db->prepare("SELECT name FROM cats WHERE id = ?");
		$statement->execute([$catid]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$catname = $result->name;

		$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $result->name;


	//---------------------------------
		$statement = parent::$db->prepare("INSERT INTO smsdetails (product_id,promo_id,promodetail_id,user_id,mobile,imei,sno,wperiod,brand_id,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,NOW(),NOW())"); 
		$statement->execute(array($product_id,0,0,$user_id,$msisdn,$imei,$sno,$wperiod,$brand_id));
//---------------------------------

$wper=$wperiod."days";
$date = date('Y-m-d', strtotime($wper));
if($brandname=="Motorola"){
   $response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");
}
else{
	$response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");
}
			echo json_encode($response);
			//return "Thank you for purchasing $product_name, your warrnaty is activated";
			//self::get_error_msg($mobileno,$msg="Thank you for purchesing $product_name, your warrnaty is activated");
} else {

	//-------------------------------------------
		$statement = parent::$db->prepare("SELECT brand_id,name,cat_id FROM products WHERE id = ?");
		$statement->execute([$product_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brand_id = $result->brand_id;
		@$product_name = $result->name;
		@$catid = $result->cat_id;
//-------------------------------------------
//
	$statement = parent::$db->prepare("SELECT name FROM cats WHERE id = ?");
		$statement->execute([$catid]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$catname = $result->name;

		$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $result->name;

//---------------------------------
		$statement = parent::$db->prepare("INSERT INTO smsdetails (product_id,promo_id,promodetail_id,user_id,mobile,imei,sno,wperiod,brand_id,status,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,?,NOW(),NOW())"); 
		$statement->execute(array($product_id,$promo_id,$id,$user_id,$msisdn,$imei,$sno,$wperiod,$brand_id,1));
//---------------------------------
		$wper=$wperiod."days";
		//$date = date('Y-m-d', strtotime("$wper +50 day"));
		$date = date('Y-m-d', strtotime($wper));
		if($brandname=="Motorola"){
           //Retailer Gift Code
          
           /*	$smsArray = array(
					    'message_body' => "Congratulations! You have won $details for $product_name ($sno) activation",
					    'recipient' => [$retail_contact],
					    'sender' => 'MOTOROLA',
						);
						$rdata = self::MazegeekSmsGateway($smsArray);*/

           //Retailer Gift Code
						

$response=  array("status"=>"4000","response"=>"Your e-warranty Valid till $date.  You have won $details. For more info 09638776600");

          /* $response=  array("status"=>"4000","response"=>"Your e-warranty has been activated with 50 days extra warranty ,Valid till $date. Congratulations!! You Have won $details from Bijoy Ullash campaign. You will receive your gift within 7 days. For more info 01810034000");*/

        }else{
       	   $response=  array("status"=>"4000","response"=>"Your e-warranty Valid till $date.  You have won $details. For more info 09638776600");
       }

		//  ============= updated code ===================
			self::check_limit_qty($product_id);
		//  ============= updated code ===================


			echo json_encode($response);
			//return "Thank you for purchasing orginal $product_name, your warrnaty is activated. Congratulation!! You will get $details. Please contact our retail partner.";
			//self::get_error_msg($mobileno,$msg="Thank you for purchesing $product_name, your warrnaty is activated. $details");
//---------------------------------
}


}


} else {
	//-------------------------------------------
		$statement = parent::$db->prepare("SELECT brand_id,name,cat_id FROM products WHERE id = ?");
		$statement->execute([$product_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brand_id = $result->brand_id;
		@$product_name = $result->name;
		@$catid = $result->cat_id;
//-------------------------------------------
//
	$statement = parent::$db->prepare("SELECT name FROM cats WHERE id = ?");
		$statement->execute([$catid]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$catname = $result->name;


$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $result->name;

//---------------------------------

		$statement = parent::$db->prepare("INSERT INTO smsdetails (product_id,promo_id,promodetail_id,user_id,mobile,imei,sno,wperiod,brand_id,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?,NOW(),NOW())"); 
		$statement->execute(array($product_id,0,0,$user_id,$msisdn,$imei,$sno,$wperiod,$brand_id));
//---------------------------------
		$wper=$wperiod." days";
		$date = date('Y-m-d', strtotime($wper));
		if($brandname=="Motorola"){
		$response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");

			//$response=  array("status"=>"4000","response"=>"Thank you for purchasing Original $company $catname. Congratulation!! your warranty is valid till $date ");
	}
	else{
	$response=  array("status"=>"4000","response"=>"Congratulations! Your e-warranty  has been activated, valid till $date. For more info 09638776600");
		
	}
			echo json_encode($response);

			//return "Thank you for purchasing $product_name, your warrnaty is activated";
			//self::get_error_msg($mobileno,$msg="Thank you for purchesing $product_name, your warrnaty is activated");
}


//-------------------
			}else{
				$response=  array("status"=>"4000","response"=>"warranty is already Activated!! For Purchasing More Product visit our website https://salextra.com.bd");
			echo json_encode($response);
				//return "This product is already activated, please concern with your retailer";
				//self::get_error_msg($mobileno,$msg="This product is already activated, please concern with your retailer");
			}


		}else{
			$response=  array("status"=>"4000","response"=>"IMEI or serial no is not valid");
			echo json_encode($response);
			//return "IMEI or serial no is not valid";
			//self::get_error_msg($mobileno,$msg="IMEI or serial no is not valid");
		}


	}else{
		$response=  array("status"=>"4000","response"=>"Message not valid please contact with your retailer");
			echo json_encode($response);
		//return "Message not valid please concern with your retailer";
		//self::get_error_msg($mobileno,$msg="Message not valid please concern with your retailer");
	}

}else{

	if($keyword == "XTRA"){$msg="Salextra Fan";
$company="Salextra";}
elseif($keyword == "MOTO"){$msg="Motorola Fan";
$company="Motorola";}
elseif($keyword == "SOF"){$msg="Sofel Fan";
$company="Sofel";}
elseif($keyword == "HWE"){$msg="Huawei Fan";
$company="Huawei";}
elseif($keyword == "LNV"){$msg="Lenovo Fan";
$company="Lenovo";}
elseif($keyword == "DIZO"){$msg="DIZO Fan";
$company="DIZO";}


	if($retailid=='V'){
		$statement = parent::$db->prepare("SELECT id, product_id, imei, wperiod FROM stocks where sno = ?");   //ORDER BY post_id DESC  or ASC  // WHERE cat_id=?
		$statement->execute([$sno]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);


		@$product_id = $result->product_id;
	

		$statement = parent::$db->prepare("SELECT brand_id,name,cat_id FROM products WHERE id = ?");
		$statement->execute([$product_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brand_id = $result->brand_id;
		@$product_name = $result->name;
		@$catid = $result->cat_id;


$statement = parent::$db->prepare("SELECT name FROM brands WHERE id = ?");
		$statement->execute([$brand_id]);
		//$count = $statement->rowCount(PDO::FETCH_OBJ);
		$result = $statement->fetch(PDO::FETCH_OBJ);

		@$brandname = $result->name;
		
		if($count>0){
			$response=  array("status"=>"4000","response"=>"Thank You For Your Query.This is an Original $brandname $product_name. For More Details Visit http://salextra.xyz/verify");
			echo json_encode($response);

		}
		else{
			$response=  array("status"=>"4000","response"=>"Thank You For Your Query.This is Not an Original $brandname Product!!");
			echo json_encode($response);	
		}

	}
	elseif ($retailid == 'PRE') {
// Code For RP ========
		//Color	//--------------------------------
/*		if($color != 'BLACK' && $color !='GREEN' && $color !='PINK'){
				$response=  array("status"=>"4000","response"=>"Sorry. Color Should be GREEN, BLACK & PINK");
						echo json_encode($response);
						exit;
		}*/
/*
$statement = parent::$db->prepare("SELECT color,count FROM colors WHERE color=?"); 
		$statement->execute([$color]);
$result = $statement->fetch(PDO::FETCH_OBJ);
$counter = $result->count;
*/

		/*$response=  array("status"=>"4000","response"=>"PRE Booking Stock of Nova 7i is Sold Out.Thank Your For Your Overwhelming Response");
						echo json_encode($response);
						exit;*/

		$officeid = $sno;

		$statement = parent::$db->prepare("SELECT id FROM promorts WHERE status = ? AND CURDATE() BETWEEN sdate AND edate"); 
		$statement->execute([1]);
		$count = $statement->rowCount(PDO::FETCH_OBJ);
		$promortresult = $statement->fetch(PDO::FETCH_OBJ);
		
	/*	$promort_id = $promortresult->id;

			$statement = parent::$db->prepare("SELECT id FROM promortsmsdetails where promort_id = ? AND color = ?");
						$statement->execute([$promort_id, $color]);
						$qtycount = $statement->rowCount(PDO::FETCH_OBJ);
if($counter<=$qtycount){
$response=  array("status"=>"4000","response"=>"Sorry $color color is Stock Out");
						echo json_encode($response);
						exit;
}*/


//Color//----------------------
	if ($count > 0) {
//============
		
			$promort_id = $promortresult->id;



			$statement = parent::$db->prepare("SELECT id FROM users where officeid = ? AND level= ? AND active='1'");
			$statement->execute([$officeid,200]);
			$count = $statement->rowCount(PDO::FETCH_OBJ);
			$result = $statement->fetch(PDO::FETCH_OBJ);

			if ($count > 0) {
				
				$user_id = $result->id;
				$statement = parent::$db->prepare("SELECT id FROM promortretailers where promort_id = ? AND user_id = ?");
				$statement->execute([$promort_id,$user_id]);
				$count = $statement->rowCount(PDO::FETCH_OBJ);
				//$result = $statement->fetch(PDO::FETCH_OBJ);


				if ($count > 0) {
// last1 ============
					//-------------------
						$statement = parent::$db->prepare("SELECT id, limitperday,quantity,promort_id,details FROM promortdetails WHERE promort_id = ? AND status = ? AND status1 = ? ORDER BY RAND()");
						$statement->execute([$promort_id,1,1]);
						//$count = $statement->rowCount(PDO::FETCH_OBJ);
						$result = $statement->fetch(PDO::FETCH_OBJ);
						//$frresult = $statement->fetch(PDO::FETCH_OBJ);

						@$id = $result->id;
						@$id1 = $result->id;
						@$promort_id = $result->promort_id;
						@$limitperday = $result->limitperday;
						@$quantity = $result->quantity;
						@$details = $result->details;
					//-------------------
				
					//-------------------
						$statement = parent::$db->prepare("SELECT id FROM promortsmsdetails where promort_id = ? AND promortdetail_id = ?");
						$statement->execute([$promort_id, $id1]);
						$qtycount = $statement->rowCount(PDO::FETCH_OBJ);


					//-------------------
						
			if ( $quantity >= $qtycount ) {

					//-------------------
						$statement = parent::$db->prepare("SELECT id FROM promortsmsdetails where promort_id = ? AND promortdetail_id = ? AND DATE_FORMAT(created_at,'%Y-%m-%d') = CURDATE()");
						$statement->execute([$promort_id, $id1]);
						$limitcount = $statement->rowCount(PDO::FETCH_OBJ);
						
						
					//-------------------
						
					if ($limitperday >= $limitcount ) {
// last2 ============
					//---------------------------------
						$statement = parent::$db->prepare("INSERT INTO promortsmsdetails (user_id,promort_id,promortdetail_id,details,phoneno,color,created_at,updated_at) VALUES(?,?,?,?,?,?,NOW(),NOW())"); 
						$statement->execute(array($user_id,$promort_id,$id,$details,$msisdn,$color));
					//---------------------------------
					
						//update=====
						$statement = parent::$db->prepare("SELECT id FROM promortsmsdetails where promort_id = ? AND promortdetail_id = ? AND DATE_FORMAT(created_at,'%Y-%m-%d') = CURDATE()");
						$statement->execute([$promort_id, $id1]);
						$limitcount = $statement->rowCount(PDO::FETCH_OBJ);

						if ( $limitperday == $limitcount){
							$statement = parent::$db->prepare("UPDATE promortdetails SET status1 = ? WHERE id = ?");
							$statement->execute(array(0,$id));
						}
						//update=====

						//update=====

						$statement = parent::$db->prepare("SELECT id FROM promortsmsdetails where promort_id = ? AND promortdetail_id = ?");
						$statement->execute([$promort_id, $id1]);
						$qtycount = $statement->rowCount(PDO::FETCH_OBJ);
						if ( $quantity == $qtycount ){
							$statement = parent::$db->prepare("UPDATE promortdetails SET status1 = ? WHERE id = ?");
							$statement->execute(array(0,$id));
						}
						//update=====


					//message =======
						//return "Congratulation!! You will get $details Please contact with retailer.";

						$response=  array("status"=>"4000","response"=>"$details ");
						echo json_encode($response);
						
						//self::get_error_msg($mobileno,$msg="Congratulation!! You will get $details Please contact with retailer.");
					//message =======
// last2 ============

					}else{
						//message =======
						//return "Sorry limit per day of gift has already been over.";

						$response=  array("status"=>"4000","response"=>"Sorry limit per day of gift has already been over.");
						echo json_encode($response);
						exit;

						//self::get_error_msg($mobileno,$msg="Sorry limit per day of gift has already been over.");
					//message =======
						
					}
				}else{
					//message =======
						//return "Sorry quentity of gift has already been over.";

						$response=  array("status"=>"4000","response"=>"Sorry quantity of gift has already been over.");
						echo json_encode($response);
						exit;

						//self::get_error_msg($mobileno,$msg="Sorry quentity of gift has already been over.");
					//message =======
				}

// last1 ============
				}else{
					//return "Sorry you are not elegible for this promotion";
					$response=  array("status"=>"4000","response"=>"Sorry Your Shop Code is Wrong.");
					echo json_encode($response);
					exit;
				}
			}else{
				//return "Sorry you are not elegible for this promotion";
				$response=  array("status"=>"4000","response"=>"Sorry Your Shop Code is Wrong.");
				echo json_encode($response);
				exit;
			}
//============	

	}else{
		//return "Sorry there is no promotion running at this time.";
		$response=  array("status"=>"4000","response"=>"Sorry there is no promotion running at this time.");
		echo json_encode($response);
		exit;
	}

// Code For RP ========
	}
	else
	{
		$response=  array("status"=>"4000","response"=>"Retailer code is not valid!!please contact with your retailer.");
		echo json_encode($response);
	}
}



}



	private static function get_error_msg($mobileno,$msg){
		$phoneno = str_replace("+", "", $mobileno); 
    $getdata = http_build_query(
			array(
		    	'masking' => 'SMART TECH',
		    	'userName' => 'SmartTech_Sofel',
				 	'password'=>'46fb610d839ea46f08f7ab8810686e19',
				 	'MsgType'=>'TEXT',
				 	'receiver'=>$phoneno,
				 	'message'=>$msg,
				)
		);

		$opts = array('http' =>
		  array(
		    'method'  => 'GET',
		    'header' => 'Content-Type: application/x-www-form-urlencoded',
		    'content' => $getdata
			)
		);

		$context  = stream_context_create($opts);
		 
		file_get_contents ('http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?'.$getdata, false, $context);

	}


	private static function MazegeekSmsGateway($smsArray){
		
		/*username: salextra-mzk21
		password: aaaaaa*/
		
		$url = 'https://sms.mazegeek.com/api/create-token';
		$postRequest = array(
		    'username' => 'salextra-mzk21',
		    'password' => 'aaaaaa'
		);


		$cURLConnection = curl_init($url);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);

		// $apiResponse - available data from the API request
		$jsonArrayResponse = json_decode($apiResponse);

		//dd($jsonArrayResponse);

		$token = $jsonArrayResponse->token;
		
		$sendurl = 'https://sms.mazegeek.com/api/send-sms';
		
		$header_array = [
			"Authorization: Bearer {$token}"
		];

		$cURLConnection = curl_init($sendurl);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cURLConnection,CURLOPT_HTTPHEADER,$header_array);
		curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,http_build_query($smsArray));
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$apiResponse = curl_exec($cURLConnection);
		curl_close($cURLConnection);

		// $apiResponse - available data from the API request
		$jsonArrayResponse = json_decode($apiResponse);

		return $jsonArrayResponse->success;
		//dd($jsonArrayResponse);

	}

	private static function check_limit_qty($product_id){
	//promodetails check

		$statement = parent::$db->prepare("SELECT id, limitperday,quantity,promo_id,details FROM promodetails WHERE product_id = ? AND status = ? AND CURDATE() BETWEEN sdate AND edate");
		$statement->execute([$product_id,1]);
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);


		for ($i=0; $i < count($results) ; $i++) { 
			
			/*@$id = $result->id;
			@$id1 = $result->id;
			@$promo_id = $result->promo_id;
			@$limitperday = $result->limitperday;
			@$quantity = $result->quantity;*/


			$id = $results[$i]["id"];
			$id1 = $results[$i]["id"];
			$promo_id = $results[$i]["promo_id"];
			$limitperday = $results[$i]["limitperday"];
			$quantity = $results[$i]["quantity"];




			// limitperday check
				$statement = parent::$db->prepare("SELECT id FROM smsdetails where promodetail_id = ? AND DATE_FORMAT(created_at,'%Y-%m-%d') = CURDATE()");
				$statement->execute([$id1]);
				$count = $statement->rowCount(PDO::FETCH_OBJ);

				if ($count >= $limitperday) {
					$statement = parent::$db->prepare("UPDATE promodetails SET status1 = ? WHERE id = ?"); 
					$statement->execute(array(0,$id));

				}

			// limitperday check
				$statement = parent::$db->prepare("SELECT id FROM smsdetails where promodetail_id = ?");
				$statement->execute([$id1]);
				$count = $statement->rowCount(PDO::FETCH_OBJ);

				if ($count >= $quantity) {
					$statement = parent::$db->prepare("UPDATE promodetails SET status1 = ? WHERE id = ?"); 
					$statement->execute(array(0,$id));

				}

		}


	}




}


?>


