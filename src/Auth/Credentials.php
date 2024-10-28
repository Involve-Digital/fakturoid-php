<?php

namespace Fakturoid\Auth;

use DateTimeImmutable;
use Fakturoid\Enum\AuthTypeEnum;
use Fakturoid\Exception\InvalidDataException;

class Credentials
{
    public const DATE_FORMAT_ATOM = 'Y-m-d\TH:i:sP';

    /** @var string|null */
    private $refreshToken;

    /** @var string|null */
    private $accessToken;

    /** @var DateTimeImmutable */
    private $expireAt;

    /** @var AuthTypeEnum */
    private $authType;

    public function __construct(
        ?string $refreshToken,
        ?string $accessToken,
        DateTimeImmutable $expireAt,
        AuthTypeEnum $authType
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

    public function getAuthType(): AuthTypeEnum
    {
        return $this->authType;
    }

    public function setAuthType(AuthTypeEnum $type): void
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
            'expireAt' => $this->expireAt->format(self::DATE_FORMAT_ATOM),
            'authType' => $this->authType->value,
        ]);

        if ($json === false) {
            throw new InvalidDataException('Failed to encode credentials to JSON: ' . json_last_error_msg());
        }

        return $json;
    }
}
