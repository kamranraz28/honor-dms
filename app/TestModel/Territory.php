<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Territory extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['region_id', 'territory'];



  public function region(){
    return $this->belongsTo('\App\Region');
  }



}
