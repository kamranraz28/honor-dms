<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name'];

  public function product(){
    return $this->hasMany('\App\Product');
    //return $this->belongsTo('\App\Product');
  }
}
