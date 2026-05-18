<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Product;

class ProductsPhoto extends Model
{
  protected $fillable = ['product_id','filename'];
   //protected $guarded = [];
  

  public function product(){
  	return $this->belongsTo('App\Product');
  }
   
}
