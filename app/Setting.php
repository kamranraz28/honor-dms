<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  
  //protected $fillable = []
  //protected $guarded = []
	public $timestamps = true;

  //protected table = 'tbl_user';
  //protected $primaryKey = 'user_id';
  
  protected $fillable = ['timezone','currency','code','vat','hotline','contact','semail','outdhaka','indhaka','serviceindhaka','favicon','logo'];


}
