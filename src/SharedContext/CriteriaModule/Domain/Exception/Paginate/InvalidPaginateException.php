<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Paginate;


use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidPaginateException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_PAGINATE');
    }

    public static function offset(string $message): static
    {
        return new static($message);
    }

    public static function limit(string $message): static
    {
        return new static($message);
    }
}
