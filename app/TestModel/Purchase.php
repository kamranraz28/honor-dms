<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['purch_id','invoice_id','invo_id','user_id', 'region_id','territory_id','distributor_id','date','date1','total','vat_amount','deposit','bslip','chalan_no','chalan_date'];




  public function invoice(){
    return $this->belongsTo('\App\Invoice');
  }

  public function distributor(){
    return $this->belongsTo('\App\Distributor');
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

  public function purchaseproduct(){
    return $this->hasMany('\App\Purchaseproduct');
  }


}
