<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promortdetail extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['promort_id','user_id','amount','limitperday','quantity','details','status','sdate','edate','image'];

  public function promort(){
    return $this->belongsTo('\App\Promort');
    //return $this->belongsTo('\App\Product');
  }
  public function user(){
    return $this->belongsTo('\App\User')->select('id','officeid');
    //return $this->belongsTo('\App\Product');
  }
}
