<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\Contract;

use Throwable;

trait IsValidTrait
{
    final public function isValid(): bool
    {
        try {
            $this->validate();
            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
