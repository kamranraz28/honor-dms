<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['id','user_id','name','email','retailer_id','officeid'];

  public function user()
  {
      return $this->belongsTo(User::class, 'retailer_id','id');
  }
  public function dealer()
  {
      return $this->belongsTo(User::class, 'user_id','id');
  }


}
