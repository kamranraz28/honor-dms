<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
  
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['duid','territory_id', 'region_id','distributor','owner','address','contact','dob','trade','tin','bin','nid','bname','baccount','photo'];



  public function region(){
    return $this->belongsTo('\App\Region');
  }

  public function territory(){
    return $this->belongsTo('\App\Territory');
  }

}
