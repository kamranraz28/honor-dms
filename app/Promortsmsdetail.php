<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promortsmsdetail extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['user_id','promort_id','promortdetail_id','details'];

  public function user(){
    return $this->belongsTo('\App\User')->select('officeid','id','firstname');
  }

  public function promort(){
    return $this->belongsTo('\App\Promort')->select('id','name');
  }

  public function promortdetail(){
    return $this->belongsTo('\App\Promortdetail')->select('id','details');
  }

  public function promortkey(){
    return $this->belongsTo('\App\Promortkey')->select('id','name');
  }

}
