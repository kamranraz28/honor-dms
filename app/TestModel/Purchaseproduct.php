<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchaseproduct extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'did';
  protected $fillable = ['purchase_id', 'distributor_id','product_id','region_id','territory_id', 'product_price', 'product_qty','product_name','product_sku','carton_count','date','date1','status'];


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
