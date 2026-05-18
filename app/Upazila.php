<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['id','district_id','up_id','name','bn_name','lat','long','website'];


}
