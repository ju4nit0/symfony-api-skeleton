<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use ApiRa\SharedContext\SharedModule\Domain\ValueObject\DateTimeValueObject;
use DateTimeImmutable;
use DateTimeZone;

readonly class CreatedAt extends DateTimeValueObject
{
    public function __construct()
    {
        parent::__construct((new DateTimeImmutable())->setTimezone(new DateTimeZone('UTC')));
    }
}
