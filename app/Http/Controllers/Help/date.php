<?php  

	  echo md5('admin');
	  echo "<br>";
	  echo sha1('ringku');
	  echo "<br>";
	  date_default_timezone_set('Asia/Dhaka');
	  
	  $date = '09/21/2016';
	  $datetimetostr = strtotime($date);
	  echo date("jS F Y",$datetimetostr);
	  echo date("jS F Y",strtotime($date));
	  
	  echo "<br>";
	  echo date("D, d M Y H:i:s") ;
	  echo "<br>";
	  echo date("jS F, Y");
	  echo "<br>";
	  echo date("l");
	  echo "<br>";
	  echo $dd = date('d M Y h:i A');
	  echo "<br>";
	  echo $date = date("Y-m-d H:m:s", strtotime('-24 hours', time()));
	  echo "<br>";
	  echo $date = date("Y-m-d h:m:i A", strtotime('-24 hours', time()));

	  echo "<br>";
	  echo $date = date("D, jS F, Y h:m:i A", strtotime('-24 hours', time()));
	  
	  $date3 = '09/21/2016';
	  echo $tomorrow = date('d/m/Y',strtotime($date3 . "+1 days"));
	  
	  echo "<br>";
	  echo $date1 = strtotime('04 Feb 2016 10:36 AM');
	  echo "<br>";
	  echo $date2 = strtotime('04 Feb 2016 12:36 PM');
	  echo "<br>";
	  echo $fdate = ($date2 - $date1) /3600 ;
	  echo "<br>";
	  
	  $date1 = strtotime('2016-02-05 11:25:08 AM');
	  $date2 = strtotime(date('Y-m-d H:i:s A'));
	  echo $ffd =  ($seconds_diff = $date2 - $date1)/60;
	  echo "<br>";
	  echo round(abs($seconds_diff) / 60,2). " mins ago";
	  echo "<br>";
	  echo round($ffd );
	  echo "<br>";
	  if ($ffd > 60) {
	      $ffd = $ffd /60 ;
	      echo $fch = round($ffd,2);
	  }


		$date = "04-15-2013";
		$date1 = str_replace('-', '/', $date);
		$tomorrow = date('m-d-Y',strtotime($date1 . "+1 days"));
		$tomorrow = date('m-d-Y',strtotime($date1 . "+1 month"));

		echo $tomorrow;




?>