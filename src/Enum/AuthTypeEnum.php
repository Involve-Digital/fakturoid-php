<?php

namespace Fakturoid\Enum;

use InvalidArgumentException;

class AuthTypeEnum
{
    public const AUTHORIZATION_CODE_FLOW = 'authorization_code';
    public const CLIENT_CREDENTIALS_CODE_FLOW = 'client_credentials';

    /** @var string */
    private $value;

    /** @var string */
    private $name;

    /**
     * Private constructor to prevent direct instantiation
     *
     * @param string $name
     * @param string $value
     */
    private function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Static method to create an instance by name.
     *
     * @param string $name
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromName(string $name): self
    {
        switch ($name) {
            case 'AUTHORIZATION_CODE_FLOW':
                return new self($name, self::AUTHORIZATION_CODE_FLOW);
            case 'CLIENT_CREDENTIALS_CODE_FLOW':
                return new self($name, self::CLIENT_CREDENTIALS_CODE_FLOW);
            default:
                throw new InvalidArgumentException("Invalid enum name: $name");
        }
    }

    /**
     * Static method to create an instance by value.
     *
     * @param string $value
     * @return self
     * @throws InvalidArgumentException
     */
    public static function fromValue(string $value): self
    {
        switch ($value) {
            case self::AUTHORIZATION_CODE_FLOW:
                return new self('AUTHORIZATION_CODE_FLOW', $value);
            case self::CLIENT_CREDENTIALS_CODE_FLOW:
                return new self('CLIENT_CREDENTIALS_CODE_FLOW', $value);
            default:
                throw new InvalidArgumentException("Invalid enum value: $value");
        }
    }

    /**
     * Get the name of the enum.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of the enum.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Magic method to return a string representation similar to an enum.
     *
     * @return string
     */
    public function __toString(): string
    {
        return "Fakturoid\\Enum\\AuthTypeEnum Enum:string\n(\n    [name] => $this->name\n    [value] => $this->value\n)";
    }
}
