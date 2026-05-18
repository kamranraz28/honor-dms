<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['invo_id','user_id', 'region_id','territory_id','distributor_id','date','total','vat_amount','deposit','bslip','remarks'];




  public function distributor(){
    return $this->belongsTo('\App\Distributor');
  }

  public function bank(){
    return $this->belongsTo('\App\Bank');
  }

  public function region(){
    return $this->belongsTo('\App\Region');
  }

  public function territory(){
    return $this->belongsTo('\App\Territory');
  }

  public function invoproduct(){
    return $this->hasMany('\App\Invoproduct');
  }

  public function purchase(){
    return $this->hasOne('\App\Purchase');
  }


}
