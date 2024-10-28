<?php

namespace Fakturoid\Auth;

use DateTimeImmutable;
use Fakturoid\Exception\InvalidDataException;

class Credentials
{
    /** @var string|null */
    private $refreshToken;

    /** @var string|null */
    private $accessToken;

    /** @var DateTimeImmutable */
    private $expireAt;

    /** @var string */
    private $authType;

    public function __construct(
        ?string $refreshToken,
        ?string $accessToken,
        DateTimeImmutable $expireAt,
        string $authType
    ) {
        $this->refreshToken = $refreshToken;
        $this->accessToken = $accessToken;
        $this->expireAt = $expireAt;
        $this->authType = $authType;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function isExpired(): bool
    {
        return (new DateTimeImmutable()) > $this->expireAt;
    }

    public function getAuthType(): string
    {
        return $this->authType;
    }

    public function setAuthType(string $type): void
    {
        $this->authType = $type;
    }

    public function getExpireAt(): DateTimeImmutable
    {
        return $this->expireAt;
    }

    /**
     * @throws InvalidDataException
     */
    public function toJson(): string
    {
        $json = json_encode([
            'refreshToken' => $this->refreshToken,
            'accessToken' => $this->accessToken,
            'expireAt' => $this->expireAt->format('Y-m-d\TH:i:sP'),
            'authType' => $this->authType,
        ]);

        if ($json === false) {
            throw new InvalidDataException('Failed to encode credentials to JSON: ' . json_last_error_msg());
        }

        return $json;
    }
}
