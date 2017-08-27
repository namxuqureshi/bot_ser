<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FriendList
 */
class FriendList extends Model
{
    protected $table = 'friend_list';

    public $timestamps = true;

    protected $fillable = [
        'user1_id',
        'user2_id'
    ];

    protected $guarded = [];

        
}