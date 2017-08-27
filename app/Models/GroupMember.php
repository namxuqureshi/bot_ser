<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GroupMember
 */
class GroupMember extends Model
{
    protected $table = 'group_members';

    public $timestamps = true;

    protected $fillable = [
        'group_id',
        'user_id'
    ];

    protected $guarded = [];

        
}