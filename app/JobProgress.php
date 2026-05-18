<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProgress extends Model
{
    //
    protected $table = 'job_progress';

    protected $fillable = [
        'job_id',
        'user_id',
        'type',
        'order_number',
        'status',
        'total',
        'log_details',
        'model_error',
        'no_stock',
        'sold_list',
        'tertiary_sold_list',
        'no_dealer',
        'inserted',
        'remaining',
        'message',
        'duplicates',
        'progress',
        'started_at',
        'finished_at',
        'file_path',
    ];
}
