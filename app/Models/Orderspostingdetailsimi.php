<?php

namespace App\Models;
use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Orderspostingdetailsimi
 *
 * @property $id
 * @property $orderspostingdetails_id
 * @property $IMI
 * @property $created_by
 * @property $created_at
 *
 * @property Orderspostingdetail $orderspostingdetail
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Orderspostingdetailsimi extends Model
{
    
    static $rules = [
		'orderspostingdetails_id' => 'required',
		'imi' => 'required',
		'created_by' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['orderspostingdetails_id','imi','imi2','order_number','product_id','created_by'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderspostingdetail()
    {
        return $this->hasOne('App\Models\Orderspostingdetail', 'id', 'orderspostingdetails_id');
    }

    public function Product(){
      return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function createMany(array $data)
    {
        return static::insert($data);
    }
    

}
