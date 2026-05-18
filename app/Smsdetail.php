<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;



class Smsdetail extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
    
  protected $fillable = ['imei','product_id','brand_id','promo_id','promodetail_id','user_id','sno','wperiod','remarks','status','created_at','updated_at','mobile','isdw','dwday','dwcharge','twperiod'];
  //protected $guarded = [];

  public function user(){
    return $this->belongsTo('\App\User')->select('id','firstname','officeid');
  }

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model','color');
  }

  public function replace(){
    return $this->hasMany('\App\Replace')->select('id','smsdetail_id','brand_id','contact_name','contact_no','service_type','problem','service_status','memo','received','replace_imei2','delivery_date','remarks','void','imei','sno',DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as rplsdate'));
  }
   public function service(){
    return $this->hasMany('\App\Service')->select('id','smsdetail_id','imei','sno','contact_name','contact_no','service_type','problem','service_status',DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as rplsdate'));
  }


  public function brand(){
    return $this->belongsTo('\App\Brand')->select('id','name');
  }

  public function secondary()
{
    return $this->hasOne(\App\Sale::class, 'imei', 'imei');
}


  public function promo(){
    return $this->belongsTo('\App\Promo')->select('id',DB::raw('DATE_FORMAT(sdate,"%m/%d/%Y") as sdate,DATE_FORMAT(edate,"%m/%d/%Y") as edate'));
  }

  public function promodetail(){
    return $this->belongsTo('\App\Promodetail')->select('id','details',DB::raw('DATE_FORMAT(sdate,"%m/%d/%Y") as sdate,DATE_FORMAT(edate,"%m/%d/%Y") as edate'));
  }



}
