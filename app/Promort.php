<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promort extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name','sdate','edate','status'];

  public function promortdetail(){
    return $this->hasMany('\App\Promortdetail')->select('id','promort_id','user_id','amount','limitperday','quantity','details','status');
    //return $this->belongsTo('\App\Product');
  }

  public function promortretailer(){
    return $this->hasMany('\App\Promortretailer')->with('user')->select('id','promort_id','user_id');
  }
}
