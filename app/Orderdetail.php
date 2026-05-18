<?php

namespace App;
use App\Product;
use App\Order;

use Illuminate\Database\Eloquent\Model;

class Orderdetail extends Model
{
    public $timestamps = false;

    protected $table = 'orderdetails';
    //protected $primaryKey = 'user_id';

    protected $fillable = ['id','orader_number','product_id','discount','price','quantity','quantity_acc'];

    protected function Order(){
      return $this->belongsTo('\App\Order', 'orader_number', 'id');
    }
    protected function Product(){
      return $this->hasOne('\App\Product', 'id', 'product_id');
    }
}
