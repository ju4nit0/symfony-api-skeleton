<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Exception\Order;

use App\SharedContext\CriteriaModule\Domain\Model\Order\BaseOrder;
use App\SharedContext\SharedModule\Domain\Exception\DomainException;
use App\SharedContext\SharedModule\Domain\ValueObject\ErrorCode;

class InvalidOrderClassException extends DomainException
{
    public static function domainErrorCode(): ErrorCode
    {
        return new ErrorCode('INVALID_ORDER_CLASS');
    }

    public static function invalidOrderClass(BaseOrder $order): self
    {
        return new self(sprintf('The order class %s is not valid', $order::class));
    }
}
