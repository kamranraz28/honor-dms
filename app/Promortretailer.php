<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promortretailer extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name','officeid','user_id','promort_id'];

  public function user(){
    return $this->belongsTo('\App\User')->select('officeid','id','firstname');
  }
}
