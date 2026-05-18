<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Middistrict extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['id','district_id','user_id','name','bn_name'];


}
