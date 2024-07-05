<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Criteria;


use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class FieldCriteriaRuleException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_FIELD_CRITERIA_RULE');
    }

    public static function rulesRepeated(string $message): static
    {
        return new static($message);
    }

    public static function ruleViolation(string $message): static
    {
        return new static($message);
    }

    public static function forAssertField(string $message): static
    {
        return new static($message);
    }
}
