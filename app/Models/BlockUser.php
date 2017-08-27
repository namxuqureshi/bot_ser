<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BlockUser
 * 
 * BUTT: What this class is about ?
 * 
 */
class BlockUser extends Model
{
    protected $table = 'block_users';

    public $timestamps = true;

    protected $fillable = [
        'owner_id',
        'victim_id'
    ];

    protected $guarded = [];

        
}