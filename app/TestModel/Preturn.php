<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preturn extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['preturnid','user_id', 'region_id','territory_id','distributor_id','date','total','vat_amount','deposit','bslip','remarks'];




  public function distributor(){
    return $this->belongsTo('\App\Distributor');
  }

  public function region(){
    return $this->belongsTo('\App\Region');
  }

  public function territory(){
    return $this->belongsTo('\App\Territory');
  }

  public function preturnproduct(){
    return $this->hasMany('\App\Preturnproduct');
  }

  /*public function returnproduct(){
    return $this->hasMany('\App\Invoproduct');
  }

  public function purchase(){
    return $this->hasOne('\App\Purchase');
  }
  public function bank(){
    return $this->belongsTo('\App\Bank');
  }
  */


}
