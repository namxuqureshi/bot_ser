<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TagUserPermission
 */
class TagUserPermission extends Model
{
    protected $table = 'tag_user_permission';

    public $timestamps = true;

    protected $fillable = [
        'tag_id',
        'user_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}