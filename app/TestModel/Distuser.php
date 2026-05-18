<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distuser extends Model
{
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = false;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'did';
  
  protected $fillable = ['id', 'user_id', 'did'];
}
