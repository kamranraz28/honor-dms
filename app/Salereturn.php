<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salereturn extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['sale_id','user_id','retailer_id','stock_id','imei','product_id','sno','retailer_name'];

/*  public function user(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\User')->select('id','firstname','email','officeid');
  }*/

  public function retailer(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Retailer')->select('id','name','email','officeid');
  }
/*
  public function stock(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Stock')->select('id','imei','sno');
  }

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model');
  }*/

}
