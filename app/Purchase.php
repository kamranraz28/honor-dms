<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['user_id','order_number','dis_id','up_id','stock_id','product_id','brand_id','quantity','imei','sno' ,'status', 'created_at', 'updated_at'];

  public function user(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\User')->select('id','firstname','email','officeid');
  }

    public function district(){
    return $this->belongsTo('\App\District', 'dis_id', 'dis_id')->select('id','dis_id','name');
  }

    public function upazila(){
    return $this->belongsTo('\App\Upazila', 'up_id', 'up_id')->select('id','up_id','name');
  }



  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model','color','dp');
  }

   public function brand(){
    return $this->belongsTo('\App\Brand')->select('id','name');
  }
 
 public function order(){
  return $this->belongsTo('\App\Models\Order', 'order_number', 'id');
}

public function orderposting(){
    return $this->belongsTo('\App\Models\Ordersposting', 'order_number', 'orader_number');
  }

}
