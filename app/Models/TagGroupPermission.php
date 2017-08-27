<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TagGroupPermission
 */
class TagGroupPermission extends Model
{
    protected $table = 'tag_group_permission';

    public $timestamps = true;

    protected $fillable = [
        'tag_id',
        'group_id',
        'permission_id'
    ];

    protected $guarded = [];

        
}