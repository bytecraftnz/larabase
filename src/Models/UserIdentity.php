<?php

namespace Bytecraftnz\Larabase\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class UserIdentity extends DataModel
{
    protected $attributes = [
        'identity_id' => '',
        'id' => '',
        'user_id' => '',
        'identity_data' => null,
        'provider' => '',
        'last_sign_in_at' => '',
        'created_at' => '',
        'updated_at' => '',
        'email' => '',
    ];

    protected $fillable = [
        'identity_id',
        'id',
        'user_id',
        'identity_data',
        'provider',
        'last_sign_in_at',
        'created_at',
        'updated_at',
        'email',
    ];

    /**
     * Interact with the user's first name.
     */
    protected function userMetaData(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => new UserMetadata($value),
        );
    }
}
