<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class PrimaryTransfer extends Model
{
    //
    protected $table = 'primary_transfers';
    protected $fillable = [
        'old_user_id',
        'new_user_id',
        'imei1',
        'imei2',
        'transfered_by'
    ];


    public function olduser()
    {
        return $this->belongsTo('\App\User', 'old_user_id', 'id');
    }

    public function newuser()
    {
        return $this->belongsTo('\App\User', 'new_user_id', 'id');
    }

    public function transferUser()
    {
        return $this->belongsTo('\App\User', 'transfered_by', 'id');
    }

}
