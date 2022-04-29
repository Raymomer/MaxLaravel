<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = "users";

    protected $fillable = ['user_account', 'user_password', 'user_mail', 'expire'];

    protected $hidden = [
        'user_password',
    ];

    protected $casts = [
        'create_at' => 'datetime', 'update' => 'datetime',
    ];
}
