<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 */
class User extends Authenticatable
{
    // use Notifiable;

    public $timestamps = true;
    protected $table = 'user';
    protected $fillable = [
        'name',
        'password',
        'email',
        'phone',
        'remember_token'
    ];

    protected $guarded = ['id'];


}