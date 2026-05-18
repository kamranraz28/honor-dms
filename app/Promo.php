<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name','sdate','edate','status'];

  public function promodetail(){
    return $this->hasMany('\App\Promodetail')->with('product')->select('id','promo_id','product_id','amount','limitperday','quantity','details','status','status1');
    //return $this->belongsTo('\App\Product');
  }
}
