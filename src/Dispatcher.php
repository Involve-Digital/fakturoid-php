<?php

namespace Fakturoid;

use Fakturoid\Auth\AuthProvider;
use Fakturoid\Exception\AuthorizationFailedException;
use Fakturoid\Exception\ClientErrorException;
use Fakturoid\Exception\ConnectionFailedException;
use Fakturoid\Exception\Exception;
use Fakturoid\Exception\InvalidDataException;
use Fakturoid\Exception\RequestException;
use Fakturoid\Exception\ServerErrorException;
use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class Dispatcher implements DispatcherInterface
{
    public const BASE_URL = 'https://app.fakturoid.cz/api/v3';

    /** @var string */
    private $userAgent;

    /** @var AuthProvider */
    private $authorization;

    /** @var ClientInterface */
    private $client;

    /** @var string|null */
    private $accountSlug;

    public function __construct(
        string $userAgent,
        AuthProvider $authorization,
        ClientInterface $client,
        ?string $accountSlug = null
    ) {
        $this->userAgent = $userAgent;
        $this->authorization = $authorization;
        $this->client = $client;
        $this->accountSlug = $accountSlug;
    }

    public function setAccountSlug(string $accountSlug): void
    {
        $this->accountSlug = $accountSlug;
    }

    /**
     * @param array<string, string> $queryParams
     */
    public function get(string $path, array $queryParams = []): Response
    {
        return $this->dispatch($path, ['method' => 'GET', 'params' => $queryParams]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function post(string $path, array $data = []): Response
    {
        return $this->dispatch($path, ['method' => 'POST', 'data' => $data]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function patch(string $path, array $data): Response
    {
        return $this->dispatch($path, ['method' => 'PATCH', 'data' => $data]);
    }

    public function delete(string $path): Response
    {
        return $this->dispatch($path, ['method' => 'DELETE']);
    }

    /**
     * @param array{'method': string, 'params'?: array<string, mixed>, 'data'?: array<string, mixed>} $options
     * @throws ConnectionFailedException|InvalidDataException|AuthorizationFailedException|RequestException|Exception
     */
    private function dispatch(string $path, array $options): Response
    {
        if (strpos($path, '{accountSlug}') !== false && $this->accountSlug === null) {
            throw new Exception('Account slug is not set. You must set it before calling this method.');
        }

        $this->authorization->reAuth();
        if ($this->authorization->getCredentials() === null) {
            throw new AuthorizationFailedException('Credentials are null');
        }
        $body = null;
        if (!empty($options['data'])) {
            $body = json_encode($options['data']);

            if ($body === false) {
                throw new InvalidDataException('Failed to encode data to JSON');
            }
        }

        $url = str_replace('{accountSlug}', $this->accountSlug ?? '', sprintf('%s%s', self::BASE_URL, $path));

        if (array_key_exists('params', $options) && is_array($options['params']) && $options['params'] !== []) {
            $url .= '?' . http_build_query($options['params']);
        }

        try {
            $request = new Request(
                $options['method'],
                $url,
                [
                    'User-Agent' => $this->userAgent,
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->authorization->getCredentials()->getAccessToken()
                ],
                $body
            );

            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new ConnectionFailedException($e->getMessage(), $e->getCode(), $e);
        }
        $responseStatusCode = $response->getStatusCode();
        if ($responseStatusCode >= 400 && $responseStatusCode < 500) {
            throw new ClientErrorException($request, $response);
        }
        if ($responseStatusCode >= 500 && $responseStatusCode < 600) {
            throw new ServerErrorException($request, $response);
        }
        return new Response($response);
    }
}
