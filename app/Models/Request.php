<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Request
 */
class Request extends Model
{
    protected $table = 'requests';

    public $timestamps = true;

    protected $fillable = [
        'fromUser_id',
        'toUser_id',
        'status'
    ];

    protected $guarded = [];

        
}