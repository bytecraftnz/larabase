<?php

namespace Bytecraftnz\Larabase\Models;

class UserAppMetadata extends DataModel
{

    protected $attributes = [
        'provider' => '',
        'providers' => [],
    ];

    protected $fillable = [
        'provider',
        'providers',
    ];
    
}
