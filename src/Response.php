<?php

namespace Fakturoid;

use Fakturoid\Exception\InvalidResponseException;
use Psr\Http\Message\ResponseInterface;
use RectorPrefix20220323\Tracy\Debugger;

class Response
{

    /** @var int */
    private $statusCode;

    /** @var array<string, mixed> */
    private $headers;

    /** @var string */
    private $body;

    public function __construct(ResponseInterface $response)
    {
        $headers = [];

        $headersArray = $response->getHeaders();
        file_put_contents(__DIR__ . '/ResponseTest.log', print_r($headersArray, true), FILE_APPEND);

        foreach ($headersArray as $headerName => $value) {
            $headers[$headerName] = $response->getHeaderLine($headerName);
        }
        $statusCode = $response->getStatusCode();
        $this->statusCode = is_array($statusCode) ? (int)$statusCode[0] : (int)$statusCode;
        $this->headers = $headers;
        $this->body = $response->getBody()->getContents();
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $headerName => $value) {
            if (strtolower($headerName) == strtolower($name)) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string|array<string, mixed>|\stdClass|null
     * @throws InvalidResponseException
     */
    public function getBody(bool $returnJsonAsArray = false)
    {
        // Typically in 304 Not Modified.
        if ($this->body === '') {
            return null;
        }

        if (!$this->isJson()) {
            return $this->body;
        }

        try {
            $json = json_decode($this->body, $returnJsonAsArray, 512);

            if ($json === false) {
                throw new InvalidResponseException('Invalid JSON response');
            }
            return $json;
        } catch (InvalidResponseException $exception) {
            throw new InvalidResponseException('Invalid JSON response', $exception->getCode(), $exception);
        }
    }

    private function isJson(): bool
    {
        $contentType = $this->getHeader('Content-Type');

        return $contentType !== null && strpos($contentType, 'application/json') !== false;
    }
}
