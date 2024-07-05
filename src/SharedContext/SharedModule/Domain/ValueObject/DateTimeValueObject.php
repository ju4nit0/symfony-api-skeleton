<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Contract\SimpleValueObject;
use DateTimeImmutable;
use DateTimeInterface;

abstract readonly class DateTimeValueObject extends ValueObject implements SimpleValueObject
{
    public function __construct(public DateTimeImmutable $value)
    {
        parent::__construct();
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }
}
