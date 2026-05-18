<?php

namespace App;
use App\Brand;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  //protected $fillable = []
  //protected $guarded = []
  public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['name','cat_id','brand_id','model','product_code','dp','color','details','photo','dwcharge','dwday','chalan_type'];



  public function brand(){
    return $this->belongsTo('\App\Brand')->select('id','name');
  }

  public function cat(){
    return $this->belongsTo('\App\Cat')->select('id','name');
  }

  public function specification(){
    return $this->hasManyThrough('\App\Specification','\App\Product','id');
  }



}
