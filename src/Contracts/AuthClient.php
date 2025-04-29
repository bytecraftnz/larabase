<?php

namespace Bytecraftnz\Larabase\Contracts;

use Bytecraftnz\Larabase\Models\AuthError;
use Bytecraftnz\Larabase\Responses\AuthResponse;
use Bytecraftnz\Larabase\Responses\UserResponse;

interface AuthClient
{
    public function signUpWithEmailAndPassword(string $email, string $password, array $data): AuthResponse|AuthError;

    public function signUpWithPhoneAndPassword(string $phone, string $password, array $data = []): AuthResponse|AuthError;

    public function signOut(string $bearerToken): ?AuthError;

    public function signInWithEmailAndPassword(string $email, string $password): AuthResponse|AuthError;

    public function signInWithRefreshToken(string $refreshToken): AuthResponse|AuthError;

    public function verifyOtpViaPhone(string $otp, string $token): AuthResponse|AuthError;

    public function verifyOtpViaEmail(string $otp, string $token): AuthResponse|AuthError;

    public function resetPasswordForEmail(string $email, array $options): ?AuthError;

    public function getUser(string $bearerUserToken): array|object|null;

    public function updateUser(string $bearerToken, array $data = []): UserResponse|AuthError;

    public function updateUserPassword(string $bearerToken, string $password): UserResponse|AuthError;

    public function updateUserEmail(string $bearerToken, string $email): UserResponse|AuthError;

    public function isAuthenticated(string $bearerUserToken): bool;

    public function signInMagicLink(string $email): AuthResponse|AuthError;

    public function signInWithSMSOTP(string $phone): ?AuthError;

    public function isError($o): bool;

    public function getError(): string;
}
