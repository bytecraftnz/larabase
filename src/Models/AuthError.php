<?php

namespace Bytecraftnz\Larabase\Models;

class AuthError extends DataModel
{

    protected $attributes = [
        'code' => '',
        'message' => '',
        'status' => '',
    ];

    protected $fillable = [
        'code',
        'message',
        'status',
    ];


    
}
