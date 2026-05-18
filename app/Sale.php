<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Sale extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['user_id','ruser_id','memo','retailer_id','dis_id','up_id','sr_id','stock_id','imei','product_id','brand_id','sno','created_at','updated_at'];

  public function user(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\User')->select('id','firstname','email','officeid');
  }

  public function smsdetail()
  {
      return $this->hasOne(\App\Smsdetail::class, 'sno', 'sno');
  }


  public function salereturn(){
    return $this->hasMany('\App\Salereturn')->select('id','sale_id','retailer_id','retailer_name', 'created_at', 'updated_at' );
  }

  public function retailer(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Retailer')->select('id','name','email','officeid');
  }

   public function service(){
    return $this->hasMany('\App\Service')->select('id','sale_id','imei','sno','contact_name','contact_no','service_type','problem','service_status',DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as rplsdate'));
  }

  public function sr(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Sr', 'sr_id', 'sr_id')->select('id','sr_id','name','email','officeid');
  }

      public function district(){
    return $this->belongsTo('\App\District', 'dis_id', 'dis_id')->select('id','dis_id','name');
  }

    public function upazila(){
    return $this->belongsTo('\App\Upazila', 'up_id', 'up_id')->select('id','up_id','name');
  }
  public function retail()
  {
      return $this->belongsTo(User::class, 'ruser_id', 'id');
  }



  public function stock(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Stock')->select('id','imei','sno');
  }

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model','color');
  }

   public function brand(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Brand')->select('id','name');
  }


}
