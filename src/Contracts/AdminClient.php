<?php

namespace Bytecraftnz\Larabase\Contracts;

interface AdminClient
{
    public function getUserById(string $id): array|object|null;

    public function deleteUser(string $email, bool $softdelete = false): array|object|null;

    public function listUsers(int $page = 1, int $per_page = 5): array|object|null;

    public function createUser(string $email, string $password, array $data = []): array|object|null;

    public function inviteUserByEmail(string $email, array $options): array|object|null;

    public function updateUserById(string $id, array $data): array|object|null;

    public function generateSignUpLink(string $email, string $password, string $redirect_to): array|object|null;

    public function generateInviteLink(string $email, string $redirect_to): array|object|null;

    public function generateMagicLinkLink(string $email, string $redirect_to): array|object|null;

    public function generateRecoveryLink(string $email, string $redirect_to): array|object|null;

    public function generateEmailChangeLink(string $email, string $newEmail, string $redirect_to): array|object|null;
}
