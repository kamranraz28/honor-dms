<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['region'];

  public function territory(){
    return $this->hasMany('\App\Territory');
    //return $this->belongsTo('\App\Product');
  }
}
