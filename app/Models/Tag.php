<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag
 */
class Tag extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'tag';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'user_id',
        'ssn',
        'image_src'
    ];

    protected $guarded = [];       
}