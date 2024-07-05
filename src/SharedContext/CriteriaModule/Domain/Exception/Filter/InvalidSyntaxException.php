<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Filter;

use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidSyntaxException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_FILTER_SYNTAX');
    }

    public static function invalidGroupSyntax(string $message): static
    {
        return new static($message);
    }

    public static function invalidQuoteSyntax(string $message): static
    {
        return new static($message);
    }
}
