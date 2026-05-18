<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	//public timestamps = false;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['category'];

  public function product(){
    return $this->hasMany('\App\Product');
    //return $this->belongsTo('\App\Product');
  }
}
