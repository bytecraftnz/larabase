<?php

namespace Bytecraftnz\Larabase\Responses;

use Bytecraftnz\Larabase\Models\DataModel;
use Bytecraftnz\Larabase\Models\User;
use Bytecraftnz\Larabase\Models\UserAccess;
use Bytecraftnz\Larabase\Models\AuthError;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;


class UserResponse extends DataModel
{
    protected $attributes = [
        'user' => null,
        'error' => null,
    ];

    protected $fillable = [
        'user',
        'error',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if(isset($attributes['error'])) {
            $this->setAttribute('error', new AuthError($attributes['error']));        
            return;
        }

        $this->user = $attributes;

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