<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Exception;

use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidEmailException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_EMAIL');
    }

    public static function from(string $email): static
    {
        return new static(sprintf("The email %s is invalid.", $email));
    }
}
