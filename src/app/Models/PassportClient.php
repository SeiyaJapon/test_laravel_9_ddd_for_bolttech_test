<?php

namespace App\Models;

use Laravel\Passport\Client;

class PassportClient extends Client
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'provider',
        'personal_access_client',
        'password_client',
        'revoked',
        'created_at',
        'updated_at',
    ];
}
