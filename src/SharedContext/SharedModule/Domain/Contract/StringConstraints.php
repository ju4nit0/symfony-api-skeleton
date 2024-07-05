<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Contract;

interface StringConstraints
{
    public static function minLength(): ?int;

    public static function maxLength(): ?int;
}
