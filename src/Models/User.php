<?php

namespace Bytecraftnz\Larabase\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends DataModel implements \Illuminate\Contracts\Auth\Authenticatable
{

    protected $dateFormat = 'd/m/Y H:i:s';

    protected $attributes = [
        'id' => '',
        'app_metadata' => null,
        'user_metadata' => null,
        'identities' => [],
        'aud' => '',
        'role' => '',
        'email' => '',
        'encrypted_password' => '',
        'email_confirmed_at' => '',
        'invited_at' => null,
        'confirmation_token' => '',
        'confirmation_sent_at' => null,
        'recovery_token' => '',
        'recovery_sent_at' => null,
        'email_change_token_new' => '',
        'email_change' => '',
        'new_email' => '',
        'email_change_sent_at' => '',
        'last_sign_in_at' => '',
        'is_super_admin' => null,
        'created_at' => '',
        'updated_at' => '',
        'phone' => null,
        'phone_confirmed_at' => null,
        'phone_change' => '',
        'phone_change_token' => '',
        'phone_change_sent_at' => null,
        'confirmed_at' => '',
        'email_change_token_current' => '',
        'email_change_confirm_status' => 0,
        'banned_until' => null,
        'reauthentication_token' => '',
        'reauthentication_sent_at' => null,
        'is_sso_user' => false,
        'deleted_at' => null,
        'is_anonymous' => false,        
    ];

    protected $casts = [
        'confirmation_sent_at' => 'date:d/m/Y',
        'recovery_sent_at' => 'date:d/m/Y',
        'email_change_sent_at' => 'date:d/m/Y',
        'invited_at' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y',
        'confirmed_at' => 'date:d/m/Y',
        'email_confirmed_at' => 'date:d/m/Y',
        'phone_confirmed_at' => 'date:d/m/Y',
        'last_sign_in_at' => 'date:d/m/Y',
        'updated_at' => 'date:d/m/Y',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'id',
        'app_metadata',
        'user_metadata',
        'identities',
        'aud',
        'role',
        'email',
        'encrypted_password',
        'email_confirmed_at',
        'invited_at',
        'confirmation_token',
        'confirmation_sent_at',
        'recovery_token',
        'recovery_sent_at',
        'email_change_token_new',
        'email_change',
        'new_email',
        'email_change_sent_at',
        'last_sign_in_at',
        'is_super_admin',
        'created_at',
        'updated_at',
        'phone',
        'phone_confirmed_at',
        'phone_change',
        'phone_change_token',
        'phone_change_sent_at',
        'confirmed_at',
        'email_change_token_current',
        'email_change_confirm_status',
        'banned_until',
        'reauthentication_token',
        'reauthentication_sent_at',
        'is_sso_user',
        'deleted_at',
        'is_anonymous'
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

    /**
     * Interact with the user's first name.
     */
    protected function userAppMetaData(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => new UserAppMetadata($value),
        );
    }


    public function isEmailVerified(): bool
    {
        return $this->getAttribute('email_confirmed_at') !== null;
    }

    public function isPhoneVerified(): bool
    {
        return $this->getAttribute('phone_confirmed_at') !== null;
    }
    
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return $this->getAttribute($this->getAuthPasswordName());
    }

    public function getRememberToken()
    {
        return $this->getAttribute($this->getRememberTokenName());
    }

    public function setRememberToken($value)
    {
        $this->setAttribute($this->getRememberTokenName(), $value);
    }

    public function getRememberTokenName()
    {
        return 'rememberToken';
    }


}
