<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Contract;

interface Id
{
    public function value(): string|int;

    public function __toString(): string;
}
