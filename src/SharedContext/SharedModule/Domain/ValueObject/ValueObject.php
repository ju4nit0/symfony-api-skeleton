<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Contract\IsValidTrait;
use App\SharedContext\SharedModule\Domain\Contract\Validatable;
use App\SharedContext\SharedModule\Domain\Exception\DomainException;

abstract readonly class ValueObject implements Validatable
{
    use IsValidTrait;

    public function __construct()
    {
        $this->validate();
    }

    protected function doValidate(): void
    {
    }

    final public function validate(): void
    {
        try {
            $this->doValidate();
        } catch (DomainException $e) {
            /** @var class-string<DomainException>|null $exceptionClass */
            $exceptionClass = static::exceptionClass();

            null !== $exceptionClass && get_class($e) !== $exceptionClass
                ? throw $exceptionClass::fromPrevious($e)
                : throw $e;
        }
    }

    /** @return class-string<DomainException>|null */
    protected static function exceptionClass(): ?string
    {
        return null;
    }
}
