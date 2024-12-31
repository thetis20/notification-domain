<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Exception\NotPhoneException;

class Phone implements \Stringable
{
    private string $var;

    public static function isValid(string $var): bool
    {
        return preg_match('/^\+?[1-9]\d{1,14}$/', $var);
    }

    public function __construct(string $var)
    {
        if (self::isValid($var) === false) {
            // @codeCoverageIgnoreStart
            throw new NotPhoneException($var);
            // @codeCoverageIgnoreEnd
        }
        $this->var = $var;
    }

    public function __toString(): string
    {
        return $this->var;
    }
}
