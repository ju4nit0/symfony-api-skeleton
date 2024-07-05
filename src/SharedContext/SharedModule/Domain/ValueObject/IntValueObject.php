<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Contract\SimpleValueObject;

abstract readonly class IntValueObject extends ValueObject implements SimpleValueObject
{
    public function __construct(public int $value)
    {
        parent::__construct();
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
