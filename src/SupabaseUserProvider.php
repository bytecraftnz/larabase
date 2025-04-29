<?php

namespace Bytecraftnz\Larabase;

use Bytecraftnz\Larabase\Contracts\AuthClient;
use Bytecraftnz\Larabase\Models\AuthError;
use Bytecraftnz\Larabase\Responses\AuthResponse;
use Bytecraftnz\Larabase\Responses\UserResponse;

abstract class SupabaseUserProvider implements \Bytecraftnz\Larabase\Contracts\SupabaseUserProvider
{
    public function __construct(
        private AuthClient $authClient
    ) {}

    public function signUpWithEmailAndPassword(string $email, string $password, array $data = []): AuthResponse|AuthError
    {
        return $this->authClient->signUpWithEmailAndPassword($email, $password, $data);
    }

    public function signUpWithPhoneAndPassword(string $phone, string $password, array $data = []): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function signOut(string $bearerToken): ?AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function signInWithEmailAndPassword(string $email, string $password): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function signInWithRefreshToken(string $refreshToken): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function verifyOtpViaPhone(string $otp, string $token): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function verifyOtpViaEmail(string $otp, string $token): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function resetPasswordForEmail(string $email, array $options): ?AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function getUser(string $bearerUserToken): array|object|null
    {
        throw new \Exception('Method not implemented.');
    }

    public function updateUser(string $bearerToken, array $data = []): UserResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function updateUserPassword(string $bearerToken, string $password): UserResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function updateUserEmail(string $bearerToken, string $email): UserResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function isAuthenticated(string $bearerUserToken): bool
    {
        throw new \Exception('Method not implemented.');
    }

    public function signInMagicLink(string $email): AuthResponse|AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function signInWithSMSOTP(string $phone): ?AuthError
    {
        throw new \Exception('Method not implemented.');
    }

    public function isError($o): bool
    {
        return $o instanceof AuthError;
    }

    public function getError(): string
    {
        return 'An error occurred';
    }
}
