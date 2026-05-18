<?php

namespace App;
use DB;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;
  
  //protected $guarded = []

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  public $timestamps = true;
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'division_id','district_id','upazila_id','tso_id','firstname', 'lastname', 'contact_name', 'market_name','email','officeid', 'contact','address', 'password', 'level','dis_cat','store_type', 'photo', 'remember_token','active','status'
  ];

  
  

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];


  /*public function distuser(){
    return $this->hasManyThrough('\App\Distributor','\App\Distuser','user_id','id');
  }*/

  public function retailer(){
    return $this->hasMany('\App\Retailer')->select('id','user_id','name','officeid',DB::raw('DATE_FORMAT(created_at,"%m/%d/%Y") as date'));
  }

  public function sr(){
    return $this->hasMany('\App\Sr');
  }

  public function middistrict(){
    return $this->hasMany('\App\Middistrict')->select('name','bn_name','id','user_id');
  }

  public function tsoupazila(){
    return $this->hasMany('\App\Tsoupazila')->select('name','bn_name','id','user_id');
  }

  public function division(){
    return $this->belongsTo('\App\Division')->select('name','bn_name','id');
  }

  public function district(){
    return $this->belongsTo('\App\District')->select('name','bn_name','id');
  }

  public function upazila(){
    return $this->belongsTo('\App\Upazila')->select('name','bn_name','id');
  }

 

}
