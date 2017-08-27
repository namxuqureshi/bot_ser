<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Group
 */
class Group extends Model
{
    // BUTT:  Why not groups ??
    
    protected $table = 'group';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'owner_id'
    ];
// BUTT:  why Empty Guard ?
    protected $guarded = [];

        //BUTT:  Where are the relationships
}