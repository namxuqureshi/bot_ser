<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageUserPermission
 */
class MessageUserPermission extends Model
{
    protected $table = 'message_user_permission';

    public $timestamps = true;

    protected $fillable = [
        'message_id',
        'user_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}