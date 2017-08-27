<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageGroupPermission
 */
class MessageGroupPermission extends Model
{
    protected $table = 'message_group_permission';

    public $timestamps = true;

    protected $fillable = [
        'message_id',
        'group_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}