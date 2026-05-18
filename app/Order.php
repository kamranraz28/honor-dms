<?php

namespace App;
use App\Tsoupazila;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
         //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

    protected $table = 'orders';
    //protected $primaryKey = 'user_id';

    protected $fillable = ['upazila_id','user_id','status','remarks'];

    protected function tsoupazilas(){
      return $this->belongsTo('\App\Tsoupazila', 'upazila_id', 'upazila_id');
    }
    protected function users(){
      return $this->belongsTo('\App\User', 'upazila_id', 'id');
    }

    public function user()
  {
      return $this->belongsTo(User::class, 'upazila_id');
  }

   public function orderposting(){
      return $this->belongsTo('\App\Models\Ordersposting', 'id', 'orader_number');
    }
    public function details(){
        return $this->hasMany('App\Orderdetail', 'orader_number','id');
    }




}
