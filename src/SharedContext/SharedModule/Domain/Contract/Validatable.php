<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Contract;

interface Validatable
{
    public function isValid(): bool;

    public function validate(): void;
}
