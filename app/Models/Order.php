<?php

namespace App\Models;
use App\Tsoupazila;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    static $rules = [
		'upazila_id' => 'required',
		'user_id' => 'required',
		'status' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['upazila_id','user_id','status','remarks'];

    public function tsoupazila(){
      return $this->belongsTo(Tsoupazila::class, 'upazila_id', 'upazila_id');
    }

    public function users(){
      return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function usersd(){
      return $this->belongsTo(User::class, 'upazila_id', 'id');
    }
    public function orderposting(){
        return $this->belongsTo('\App\Models\Ordersposting', 'id', 'orader_number');
    }
    public function details(){
        return $this->hasMany('App\Orderdetail', 'orader_id','id');
    }


}
