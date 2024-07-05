<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Exception;

use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;
use Exception;
use Throwable;

abstract class DomainException extends Exception
{
    final protected function __construct(string $message, Throwable $throwable = null)
    {
        parent::__construct($message, 1, $throwable);
    }

    abstract public static function domainErrorCode(): ErrorCode;

    public static function fromPrevious(Throwable $throwable, string $message = null): static
    {
        return new static($message ?? $throwable->getMessage(), $throwable);
    }
}
