<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['imei','product_id','brand_id','sno','period','wperiod','details','created_at'];

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model','product_code','color');
  }
  public function brand(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Brand')->select('name','id');
  }
}
