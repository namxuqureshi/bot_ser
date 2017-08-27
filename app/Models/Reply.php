<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 */
class Reply extends Model
{
    protected $table = 'replies';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'message_id',
        'reply',
        'audio_src',
        'data_src'
    ];

    protected $guarded = [];

        
}