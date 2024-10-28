<?php

namespace Fakturoid\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class RequestException extends Exception implements RequestExceptionInterface
{

    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /** @var Throwable */
    private $previous;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ?Throwable $previous = null
    ) {
        parent::__construct($response->getReasonPhrase(), $response->getStatusCode(), $previous);

        $this->request = $request;
        $this->response = $response;
        $this->previous = $previous;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
