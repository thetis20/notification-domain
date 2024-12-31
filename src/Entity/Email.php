<?php

namespace Notification\Domain\Entity;

use Notification\Domain\Exception\NotEmailException;

class Email implements \Stringable
{
    private string $var;

    public static function isValid(string $var): bool
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    public function __construct(string $var)
    {
        if(self::isValid($var) === false) {
            // @codeCoverageIgnoreStart
            throw new NotEmailException($var);
            // @codeCoverageIgnoreEnd
        }

        $this->var = $var;
    }

    public function __toString(): string
    {
        return $this->var;
    }
}