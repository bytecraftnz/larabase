<?php

namespace Bytecraftnz\Larabase;

use Bytecraftnz\Larabase\Contracts\AuthClient;
use Illuminate\Contracts\Session\Session;

class LarabaseUserProvider extends SupabaseUserProvider implements \Illuminate\Contracts\Auth\UserProvider
{
    public function __construct(
        private AuthClient $authClient, 
        private Session $session
    )
    {
        parent::__construct($authClient);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // Implement logic to retrieve a user by their unique identifier
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // Implement logic to retrieve a user by their unique identifier and "remember me" token
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken($user, $token)
    {
        // Implement logic to update the "remember me" token for the user
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Implement logic to retrieve a user by their credentials
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return bool
     */
    public function validateCredentials($user, array $credentials)
    {
        // Implement logic to validate a user's credentials
    }

    /**
     * Rehash the user's password if required and supported.
     */
    public function rehashPasswordIfRequired(\Illuminate\Contracts\Auth\Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Implement logic if required, or leave empty if not applicable
    }
}
