<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Tsoupazila extends Model
{
   //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['id','upazila_id','user_id','name','bn_name'];

  protected function deleardetails(){
    return $this->hasOne(User::class, 'id', 'upazila_id');
  }

  protected function Approver(){
    return $this->hasOne(User::class, 'id', 'user_id');
  }

}
