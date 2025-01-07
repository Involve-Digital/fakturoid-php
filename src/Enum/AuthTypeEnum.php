<?php

namespace Fakturoid\Enum;

use TypeError;

class AuthTypeEnum
{
    public const AUTHORIZATION_CODE_FLOW = 'authorization_code';
    public const CLIENT_CREDENTIALS_CODE_FLOW = 'client_credentials';

    public const CASES = [
        self::AUTHORIZATION_CODE_FLOW,
        self::CLIENT_CREDENTIALS_CODE_FLOW
    ];

    /** @var string */
    public $value;

    /**
     * Private constructor to prevent direct instantiation
     *
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;

        if (!in_array($value, self::CASES, true)) {
            throw new TypeError("Invalid enum value: $value");
        }
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function tryFrom(string $value): ?self
    {
        try {
            return new self($value);
        } catch (TypeError $e) {
            return null;
        }
    }

    public static function getAuthorizationCodeFlowInstance(): self
    {
        return new self(self::AUTHORIZATION_CODE_FLOW);
    }

    public static function getClientCredentialsCodeInstance(): self
    {
        return new self(self::CLIENT_CREDENTIALS_CODE_FLOW);
    }
}
