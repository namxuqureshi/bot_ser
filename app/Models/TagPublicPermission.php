<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TagPublicPermission
 */
class TagPublicPermission extends Model
{
    protected $table = 'tag_public_permission';

    public $timestamps = true;

    protected $fillable = [
        'permission_id',
        'tag_id'
    ];

    protected $guarded = [];

        
}