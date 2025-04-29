<?php

namespace Bytecraftnz\Larabase\Models;


class UserAccess extends DataModel
{
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $attributes = [
        'access_token' => '',
        'token_type' => '',
        'expires_in' => 0,
        'expires_at' => null,
        'refresh_token' => '',
    ];

    protected $casts = [
        'expires_at' => 'date:Y-m-d H:i:s',
    ];
    
    protected $fillable = [
        'access_token',
        'token_type',
        'expires_in',
        'expires_at',
        'refresh_token',
    ];
}
