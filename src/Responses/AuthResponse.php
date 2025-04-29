<?php

namespace Bytecraftnz\Larabase\Responses;

use Bytecraftnz\Larabase\Models\AuthError;
use Bytecraftnz\Larabase\Models\DataModel;
use Bytecraftnz\Larabase\Models\User;
use Bytecraftnz\Larabase\Models\UserAccess;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Symfony\Component\VarDumper\Cloner\Data;

class AuthResponse extends DataModel
{
    
    protected $attributes = [
        'user' => null,
        'user_access' => null,
        'error' => null,
    ];

    protected $fillable = [
        'user',
        'user_access',
        'error',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);        

        if(!isset($attributes['error'])) {
            $this->setAttribute('user_access', new UserAccess(Arr::except($attributes, ['user'])));        
            return;
        }

        $this->setAttribute('error', new AuthError($attributes['error']));        
    }

    /**
     * Interact with the user's first name.
     */
    protected function user(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => new User($value),
        );
    }

    public function hasError(): bool
    {
        return $this->getAttribute('error') !== null;
    }   

}
