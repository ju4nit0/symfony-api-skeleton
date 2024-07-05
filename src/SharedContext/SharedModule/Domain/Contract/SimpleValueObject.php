<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Contract;

use DateTimeImmutable;

interface SimpleValueObject
{
    public function value(): string|int|float|DateTimeImmutable;
}
