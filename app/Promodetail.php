<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promodetail extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['promo_id','product_id','amount','limitperday','quantity','details','status','sdate','edate'];

  public function promo(){
    return $this->belongsTo('\App\Promo');
    //return $this->belongsTo('\App\Product');
  }
  public function product(){
    return $this->belongsTo('\App\Product')->select('id','name','model');
    //return $this->belongsTo('\App\Product');
  }
}
