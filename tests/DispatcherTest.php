<?php

namespace Fakturoid\Tests;

use Fakturoid\Auth\AuthProvider;
use Fakturoid\Auth\Credentials;
use Fakturoid\Dispatcher;
use Fakturoid\Exception\Exception;
use Psr\Http\Client\ClientInterface;

class DispatcherTest extends TestCase
{
    public function testRequiredAccountSlugMissing(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Account slug is not set. You must set it before calling this method.');

        $dispatcher->patch('/accounts/{accountSlug}/invoices/1.json', ['name' => 'Test']);
    }

    public function testRequiredAccountSlug(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);

        file_put_contents(__DIR__ . '/credentials.log', print_r($credentials, true), FILE_APPEND);

        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client, 'test');
        $dispatcher->patch('/accounts/{accountSlug}/invoices/1.json', ['name' => 'Test']);
    }

    public function testNotRequiredAccountSlugMissing(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);

        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client);

        $dispatcher->patch('/accounts/invoices/1.json', ['name' => 'Test']);
    }

    public function testNotRequiredAccountSlug(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);
        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client, 'test');

        $dispatcher->patch('/accounts/invoices/1.json', ['name' => 'Test']);
    }

    public function testGet(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);
        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client, 'test');
file_put_contents(__DIR__ . '/ResponseTest.log', print_r($dispatcher, true), FILE_APPEND);
        $dispatcher->get('/accounts/invoices/1.json', ['name' => 'Test']);
    }

    public function testDelete(): void
    {
        $client = $this->createMock(ClientInterface::class);

        file_put_contents(__DIR__ . '/client.log', print_r($client, true), FILE_APPEND);
        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);
        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client, 'test');

        $dispatcher->delete('/accounts/invoices/1.json');
    }

    public function testPost(): void
    {
        $client = $this->createMock(ClientInterface::class);

        $authProvider = $this->createMock(AuthProvider::class);
        $credentials = $this->createMock(Credentials::class);
        $credentials->expects($this->once())
            ->method('getAccessToken')
            ->willReturn('test');
        $authProvider->expects($this->exactly(2))
            ->method('getCredentials')
            ->willReturn($credentials);

        /**
         * @var AuthProvider $authProvider
         * @var ClientInterface $client
         */
        $dispatcher = new Dispatcher('test', $authProvider, $client, 'test');

        $dispatcher->post('/accounts/invoices/1.json', ['name' => 'Test']);
    }
}
