<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoproduct extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'did';
  protected $fillable = ['invoice_id', 'product_id', 'product_price', 'product_qty','product_name','product_sku', 'carton_count'];
}
