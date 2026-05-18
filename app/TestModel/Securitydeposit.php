<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Securitydeposit extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['securitydeposit','region_id','territory_id','distributor_id','date','remarks'];

  public function distributor(){
    return $this->belongsTo('\App\Distributor');
  }

	public function region(){
    return $this->belongsTo('\App\Region');
  }

	public function territory(){
    return $this->belongsTo('\App\Territory');
  }
}
