<?php

namespace App\Models;

use App\Models\Order;
use App\User;

use Illuminate\Database\Eloquent\Model;
use App\Orderspostingdetail;
/**
 * Class Ordersposting
 *
 * @property $id
 * @property $orader_number
 * @property $approve_by
 * @property $where_house
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ordersposting extends Model
{

    static $rules = [
		'orader_number' => 'required',
		'approve_by' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['orader_number','approve_by','where_house','remarks','status'];

    public function OrderspostingDetails(){
      return $this->hasMany('App\Orderspostingdetail', 'orderspostings_id','id');
    }

    public function Order(){
      return $this->belongsTo(Order::class, 'orader_number', 'id');
    }

    public function Userinfo(){
      return $this->belongsTo(User::class, 'approve_by', 'id');
    }

}
