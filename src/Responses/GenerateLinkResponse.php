<?php

namespace Bytecraftnz\Larabase\Responses;

use Bytecraftnz\Larabase\Models\AuthError;
use Bytecraftnz\Larabase\Models\GenerateLinkProperties;
use Bytecraftnz\Larabase\Models\User;

class GenerateLinkResponse
{
    private GenerateLinkProperties $properties;

    private User $user;

    private AuthError $auth;

    public function __construct(
        private array $data
    ) {
        $this->properties = new GenerateLinkProperties($data['properties'] ?? []);
        $this->user = new User($data['user'] ?? []);
        $this->auth = new AuthError($data['auth'] ?? []);
    }

    public function getProperties(): GenerateLinkProperties
    {
        return $this->properties;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAuthError(): AuthError
    {
        return $this->auth;
    }
}
