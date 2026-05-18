<?php

namespace App;
use App\Product;
use App\Models\Ordersposting;

use App\Models\Orderspostingdetailsimi;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Orderspostingdetail
 *
 * @property $id
 * @property $orader_number
 * @property $product_id
 * @property $quantity
 * @property $quantity_acc
 * @property $price
 * @property $price_acc
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Orderspostingdetail extends Model
{

    static $rules = [
		'orderspostings_id' => 'required',
		'product_id' => 'required',
		'quantity' => 'required',
		'price' => 'required',
    ];


    protected $perPage = 20;
    public $timestamps = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['orderspostings_id','product_id','quantity','quantity_acc','price','price_acc','remarks'];

    public function Product(){
      return $this->hasOne(Product::class, 'id', 'product_id');
    }


    public function Ordersposting(){
      return $this->belongsTo(Ordersposting::class, 'orderspostings_id', 'id');
    }

    public function imeilist(){
      return $this->hasMany(Orderspostingdetailsimi::class,'orderspostingdetails_id','id');
    }

     public function orderspostingdetailsimis()
    {
        return $this->hasMany(Orderspostingdetailsimi::class, 'orderspostingdetails_id');
    }


        public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
