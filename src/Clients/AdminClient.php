<?php

namespace Bytecraftnz\Larabase\Clients;

use Bytecraftnz\Larabase\Supabase;

final class AdminClient extends Supabase implements \Bytecraftnz\Larabase\Contracts\AdminClient
{

    /**
     * @var string
     */
    protected const adminRoutePath = '/admin';

    public function __construct( protected string $url,protected string $key,protected string $serviceKey,protected \GuzzleHttp\Client $httpClient)
    {
        parent::__construct($url, $key);
    }

    protected function getHeaders(): array
    {
        return array_merge(parent::getHeaders(), [
            'Authorization' => 'Bearer '.$this->serviceKey,
        ]);
    }

    /**
     * Get user by id
     */
    public function getUserById(string $id): array|object|null
    {
        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/users/'.$id);

        $options = [
            'headers' => $this->getHeaders(),
            'body' => null,
        ];

        return $this->doGetRequest($endPoint, $options, null);

    }

    public function deleteUser(string $id, bool $softdelete = false): array|object|null
    {
        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/users/'.$id);
        $options = [
            'headers' => $this->getHeaders(),
            'body' => json_encode([
                'should_soft_delete' => $softdelete,
            ]),
        ];

        return $this->doDeleteRequest($endPoint, $options, null);
    }

    public function listUsers(int $page = 1, int $per_page = 5): array|object|null
    {
        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/users');
        $options = [
            'headers' => $this->getHeaders(),
            'body' => null,
            'query' => [
                'page' => $page,
                'per_page' => $per_page,
            ],
        ];

        return $this->doGetRequest($endPoint, $options, null);
    }

    public function createUser(string $email, string $password, array $data = []): array|object|null
    {
        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/users');
        $options = [
            'headers' => $this->getHeaders(),
            'body' => json_encode([
                'email' => $email,
                'password' => $password,
                'data' => $data,
            ]),
        ];

        return $this->doPostRequest($endPoint, $options, null);
    }

    public function inviteUserByEmail(string $email, array $options): array|object|null
    {
        $options = [
            'headers' => $this->getHeaders(),
            'body' => json_encode([
                'email' => $email,
                'data' => $options['data'] ?? null,
            ]),
            'redirectTo' => $options['redirect_to'] ?? null,
        ];

        $endPoint = $this->buildEndpoint('/invite');

        return $this->doPostRequest($endPoint, $options, null);
    }

    public function generateSignUpLink(string $email, string $password, string $redirect_to): array|object|null
    {
        $options = [
            'email' => $email,
            'password' => $password,
            'redirect_to' => $redirect_to,
        ];

        return $this->generateLink($options);
    }

    public function generateMagicLinkLink(string $email, string $redirect_to): array|object|null
    {
        $options = [
            'email' => $email,
            'redirect_to' => $data['redirect_to'] ?? null,
        ];

        return $this->generateLink($options);
    }

    public function generateInviteLink(string $email, string $redirect_to): array|object|null
    {
        $options = [
            'email' => $email,
            'redirect_to' => $redirect_to,
        ];

        return $this->generateLink($options);
    }

    public function generateRecoveryLink(string $email, string $redirect_to): array|object|null
    {
        $options = [
            'email' => $email,
            'redirect_to' => $redirect_to,
        ];

        return $this->generateLink($options);
    }

    public function generateEmailChangeLink(string $email, string $newEmail, string $redirect_to): array|object|null
    {
        $options = [
            'email' => $email,
            'new_email' => $newEmail,
            'redirect_to' => $redirect_to,
        ];

        return $this->generateLink($options);
    }

    public function updateUserById(string $id, array $data): array|object|null
    {
        $options = [
            'headers' => $this->getHeaders(),
            'body' => json_encode([
                'data' => $data,
            ]),
        ];

        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/users/'.$id);

        return $this->doPutRequest($endPoint, $options, null);
    }

    private function buildEndpoint(string $endpoint): string
    {
        return self::baseRoutePath.$endpoint;
    }

    private function generateLink(array $options): array|object|null
    {
        $endPoint = $this->buildEndpoint(self::adminRoutePath.'/generate_link');

        $redirectTo = $options['redirect_to'] ?? null;
        unset($options['redirect_to']);

        $options = [
            'headers' => $this->getHeaders(),
            'body' => json_encode($options),
            'redirectTo' => $redirectTo,
        ];

        return $this->doPostRequest($endPoint, $options, null);

    }
}
