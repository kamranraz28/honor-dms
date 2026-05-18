<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['imei','sale_id','brand_id','product_id','smsdetail_id','sno','period','contact_name','contact_no','service_type','problem','service_status'];
  
  public function smsdetail(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Smsdetail')->with('user','product','brand')->select('id','brand_id','product_id','user_id','mobile','imei','sno','wperiod','remarks',
    	DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
						DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
						DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'));
  }
}
