<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quantity extends Model
{
  
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['product_category_id','product_id','quantity','date'];



  public function product(){
    return $this->belongsTo('\App\Product');
  }

  public function productCategory(){
    return $this->belongsTo('\App\ProductCategory');
  }




}
