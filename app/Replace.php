<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Replace extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['imei','smsdetail_id','sno','period','product_id','user_id','brand_id','contact_name','contact_no','service_type','problem','service_status','memo','received','replace_imei2','delivery_date','remarks','void','created_at','updated_at'];
  
   public function user(){
    return $this->belongsTo('\App\User', 'user_id','id')->select('id','firstname','officeid');
  }
  public function smsdetail(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Smsdetail')->with('user','product','brand')->select('id','brand_id','product_id','user_id','mobile','imei','sno','wperiod','remarks',
    	DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as saledate, DATE_FORMAT(created_at,"%D %b %y %r") as createdAt,
						DATE_FORMAT(created_at,"%m/%d/%Y") as sdate, 
						DATE_FORMAT(DATE_ADD(created_at, INTERVAL wperiod DAY),"%m/%d/%Y") as edate'));
  }

  public function product()
  {
      return $this->belongsTo('\App\Product', 'product_id', 'id');
  }

  public function brand()
  {
      return $this->belongsTo('\App\Brand', 'brand_id', 'id');
  }

  public function sms()
  {
      return $this->belongsTo('\App\Smsdetail', 'smsdetail_id', 'id');
  }

  
}
