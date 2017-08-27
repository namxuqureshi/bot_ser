<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Message
 */
class Message extends Model
{
    use SoftDeletes;
    // protected $dates = ['deleted_at','timeout'];

    protected $table = 'message';

    public $timestamps = true;

    protected $fillable = [
        'content',
        'user_id',
        'tag_id',
        'public',
        'type',
        'case_type',
        'audio_src',
        'data_src',
        'timeout'
    ];

    protected $guarded = [];

        
}