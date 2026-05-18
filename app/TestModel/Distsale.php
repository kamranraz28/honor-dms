<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distsale extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'did';
  protected $fillable = ['user_id', 'region_id','territory_id','distributor_id','date', 'product_id', 'product_price', 'product_qty','product_name','product_sku','carton_count'];



  public function distributor(){
    return $this->belongsTo('\App\Distributor');
  }

  public function region(){
    return $this->belongsTo('\App\Region');
  }

  public function territory(){
    return $this->belongsTo('\App\Territory');
  }

  public function product(){
    return $this->belongsTo('\App\Product');
  }

}
