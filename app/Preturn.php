<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Preturn extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['user_id','ruser_id','retailer_id','sr_id','stock_id','imei','product_id','brand_id','sno','status'];

  public function user(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\User')->select('id','firstname','email','officeid');
  }

  public function salereturn(){
    return $this->hasMany('\App\Salereturn')->select('id','sale_id','retailer_id','retailer_name', 'created_at', 'updated_at' );
  }

  public function retailer(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Retailer')->select('id','name','email','officeid');
  }

  public function sr(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Sr')->select('id','name','email','officeid');
  }

  public function stock(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Stock')->select('id','imei','sno');
  }

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model');
  }


}
