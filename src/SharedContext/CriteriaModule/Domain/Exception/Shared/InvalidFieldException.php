<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Shared;


use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidFieldException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_FIELD');
    }

    public static function invalidField(string $message): static
    {
        return new static($message);
    }
}
