<?php

namespace App;
use App\ProductCategory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['product_category_id', 'product','sku','price','carton_count','photo'];



  public function productCategory(){
    return $this->belongsTo('\App\ProductCategory');
  }
}
