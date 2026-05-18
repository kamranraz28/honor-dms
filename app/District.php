<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['id','division_id','dis_id','name','bn_name','lat','long','website'];


}
