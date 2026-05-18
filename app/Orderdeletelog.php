<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderdeletelog extends Model
{
    //
    protected $table = 'orderdeletelogs';
    protected $fillable = [
        'order_number',
        'deleted_by'
    ];
}
