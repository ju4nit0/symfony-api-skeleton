<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Exception;

use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidEnumException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_ENUM');
    }

    /**
     * @param array<string> $validValues
     */
    public static function notValidValue(string $value, array $validValues): static
    {
        return new static(
            sprintf(
                'The value %s is not a valid value. The available values are [%s]',
                $value,
                implode(',', $validValues),
            ),
        );
    }
}
