<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Filter;

use App\SharedContext\CriteriaModule\Domain\Model\Filter\BaseFilter;
use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidFilterClassException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_FILTER_CLASS');
    }

    public static function invalidFilterClass(BaseFilter $filter): self
    {
        return new self(sprintf('The filter class %s is not valid', $filter::class));
    }
}
