<?php

namespace Bytecraftnz\Larabase;

use Bytecraftnz\Larabase\Models\AuthError;

abstract class Supabase
{

    /**
     * @var string
     */
    const baseRoutePath = 'auth/v1';

    /**
     * @var string
     */
    private $error;

    protected function __construct(
        protected string $url, 
        protected string $key, 
        protected \GuzzleHttp\Client $httpClient
    )
    {
    }

    protected function getHeaders()
    {
        return [
            'apikey' => $this->key,
            'content-type' => 'application/json',
            'accept' => 'application/json',
        ];
    }

    protected function getHeadersWithBearer(string $bearer): array
    {
        return array_merge($this->getHeaders(), [
            'Authorization' => 'Bearer '.$bearer,
        ]);
    }

    protected function buildUrl(string $endpoint): string
    {

        return $this->url.'/'.self::baseRoutePath.'/'.ltrim($endpoint, '/');
    }

    protected function doRequest(string $method, string $endpoint, array $options): array | null
    {

        $url = $this->buildUrl($endpoint);
        $headers = $this->getHeaders();
        $body = null;

        if (isset($options['headers'])) {
            $headers = array_merge($headers, $options['headers']);
            unset($options['headers']);
        }

        $redirectTo = null;

        if (isset($options['redirect_to'])) {
            $redirectTo = $options['redirect_to'];
        }

        if (isset($options['body'])) {
            $body = json_encode($options['body']);
            unset($options['body']);
        }

        try {
            $response = $this->httpClient->request(
                $method,
                $url,
                [
                    'headers' => $headers,
                    'body' => $body,
                    'redirectTo' => $redirectTo,
                ]
            );
            if($endpoint == 'user'){
                //dd($url, $method ,json_decode($response->getBody(), true));
            }
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->extractErrorFromRequestException($e);
            
            return [
                'error' => [
                    'message' => $this->getError(),
                    'code' => $e->getCode(),
                    'status' => $e->getResponse()->getStatusCode(),
                ]
            ];
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->error = $e->getMessage();

            return [
                'error' => [
                    'message' => $this->error,
                    'code' => $e->getCode(),
                    'status' => 'Connection Error',
                ]
            ];
        }
    }

    protected function doPostRequest(string $endpoint, array $options): array | null
    {
        return $this->doRequest('POST', $endpoint, $options);
    }

    protected function doDeleteRequest(string $endpoint, array $options): array | null
    {
        return $this->doRequest('DELETE', $endpoint, $options);
    }

    protected function doPutRequest(string $endpoint, array $options): array | null
    {
        return $this->doRequest('PUT', $endpoint, $options);
    }

    protected function doGetRequest(string $endpoint, array $options): array | null
    {
        return $this->doRequest('GET', $endpoint, $options);
    }

    public function extractErrorFromRequestException(\GuzzleHttp\Exception\RequestException $e): void
    {
        if ($e->hasResponse()) {
            $res = json_decode($e->getResponse()->getBody());
            $searchItems = ['msg', 'message', 'error_description'];
            foreach ($searchItems as $item) {
                if (isset($res->$item)) {
                    $this->error = $res->$item;
                    break;
                }
            }
        }
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function isError($o): bool
    {
        return $o instanceof AuthError;
    }

}
