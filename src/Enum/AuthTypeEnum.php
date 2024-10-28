<?php

namespace Fakturoid\Enum;

use TypeError;

class AuthTypeEnum
{
    private const ENUM = [
        'AUTHORIZATION_CODE_FLOW' => 'authorization_code',
        'CLIENT_CREDENTIALS_CODE_FLOW' => 'client_credentials'
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

        if (!in_array($value, self::ENUM, true)) {
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

    public static function AUTHORIZATION_CODE_FLOW(): self
    {
        return new self(self::ENUM['AUTHORIZATION_CODE_FLOW']);
    }

    public static function CLIENT_CREDENTIALS_CODE_FLOW(): self
    {
        return new self(self::ENUM['CLIENT_CREDENTIALS_CODE_FLOW']);
    }
}
