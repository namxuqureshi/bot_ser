<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class View
 */
class View extends Model
{
    protected $table = 'views';

    public $timestamps = true;

    protected $fillable = [
        'message_id',
        'user_id'
    ];

    protected $guarded = [];

        
}