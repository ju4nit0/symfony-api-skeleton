<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Exception;

use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidStringException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_STRING');
    }

    public static function minLength(string $message, int $constraint): static
    {
        return new static(sprintf('The text "%s" is short, need %s or more chars.', $message, $constraint));
    }

    public static function maxLength(string $message, int $constraint): static
    {
        return new static(sprintf('The text "%s" is long, need %s or less chars.', $message, $constraint));
    }

    public static function empty(string $message): static
    {
        return new static(sprintf('The text "%s" is empty.', $message));
    }

    public static function fixed(string $message, int $constraint): static
    {
        return new static(sprintf('The text "%s" need %s chars.', $message, $constraint));
    }
}
