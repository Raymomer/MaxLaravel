<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = 'user_account';
    public $incrementing = false;


    protected $fillable = ['user_account', 'user_password', 'user_id', 'expire'];

    protected $hidden = [
        'user_password',
    ];

    protected $casts = [
        'create_at' => 'datetime', 'update' => 'datetime',
    ];
}
