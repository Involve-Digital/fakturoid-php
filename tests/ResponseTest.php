<?php

namespace Fakturoid\Tests;

use Fakturoid\Exception\InvalidResponseException;
use Fakturoid\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RectorPrefix20220323\Tracy\Debugger;

class ResponseTest extends TestCase
{
    public function testJson(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $responseInterface
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('application/json; charset=utf-8');
        $responseInterface
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['content-type' => ['application/json; charset=utf-8']]);
        $responseInterface
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->getStreamMock('{"name":"Test"}'));

        /** @var ResponseInterface $responseInterface */
        $response = new Response($responseInterface);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals((object) ['name' => 'Test'], $response->getBody());
    }

    public function testJsonWithMixedHeadersCase(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn([200]);
        $responseInterface
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn(['content-type' => ['application/json; charset=utf-8']]);
        $responseInterface
            ->expects($this->once())
            ->method('getHeaderLine')
            ->willReturn('application/json; charset=utf-8');
        $responseInterface
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->getStreamMock('{"name":"Test"}'));

        /** @var ResponseInterface $responseInterface */
        $response = new Response($responseInterface);

        file_put_contents(__DIR__ . '/ResponseTest.log', print_r($response, true), FILE_APPEND);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json; charset=utf-8', $response->getHeader('Content-Type'));
        $this->assertEquals('application/json; charset=utf-8', $response->getHeader('content-type'));
        $this->assertEquals('application/json; charset=utf-8', $response->getHeader('cOnTeNt-TyPe'));
        $this->assertEquals((object) ['name' => 'Test'], $response->getBody());
    }

    /**
     * @throws InvalidResponseException
     */
    public function testOther(): void
    {
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $responseInterface
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($this->getStreamMock('Test'));

        /** @var ResponseInterface $responseInterface */
        $response = new Response($responseInterface);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertNull($response->getHeader('Content-Type'));
        $this->assertEquals('Test', $response->getBody());
    }

    protected function getStreamMock(string $content): StreamInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($content);

        /** @var StreamInterface $stream */
        return $stream;
    }
}
