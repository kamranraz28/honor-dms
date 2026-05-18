<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name','product_id','specificationdetails','details'];

  public function product(){
    //return $this->hasMany('\App\Product');
    return $this->belongsTo('\App\Product')->select('name','id','model');
  }
}
