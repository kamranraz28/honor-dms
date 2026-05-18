<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;



class Dwdetail extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
    
  protected $fillable = ['imei','product_id','brand_id','user_id','customer','mobile','sno','dwduration','dwday','dwcharge','status','created_at','updated_at'];
  //protected $guarded = [];

  public function user(){
    return $this->belongsTo('\App\User')->select('id','firstname','officeid');
  }

  public function brand(){
    return $this->belongsTo('\App\Brand')->select('id','name');
  }

  public function product(){
    return $this->belongsTo('\App\Product')->select('id','name','model');
  }

  


}
