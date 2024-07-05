<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Exception;

use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;
use DateTimeInterface;

class InvalidUuidException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('UUID_INVALID');
    }

    public static function byValue(string $value): static
    {
        return new static(sprintf('The value %s is a uuid format invalid', $value));
    }

    public static function versionExpected(string $uuid, int $expected, int $current): static
    {
        return new static(
            sprintf('The uuid %s is a version %s, the expected version is %s', $uuid, $current, $expected),
        );
    }

    public static function futureId(string $uuid, DateTimeInterface $dateTime): static
    {
        return new static(
            sprintf('The uuid %s has a future timestamp %s', $uuid, $dateTime->format(DateTimeInterface::ATOM)),
        );
    }
}
