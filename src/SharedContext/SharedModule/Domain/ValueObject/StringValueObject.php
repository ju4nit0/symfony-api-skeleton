<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Contract\SimpleValueObject;
use App\SharedContext\SharedModule\Domain\Contract\StringConstraints;
use App\SharedContext\SharedModule\Domain\Exception\InvalidStringException;

abstract readonly class StringValueObject extends ValueObject implements SimpleValueObject, StringConstraints
{
    public function __construct(public string $value)
    {
        parent::__construct();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    protected function doValidate(): void
    {
        parent::doValidate();
        $valueLength = mb_strlen($this->value);

        if (null !== static::fixedLength() && static::fixedLength() !== $valueLength) {
            throw InvalidStringException::fixed($this->value, static::fixedLength());
        }

        if (null !== static::minLength() && static::minLength() > $valueLength) {
            throw InvalidStringException::minLength($this->value, static::minLength());
        }

        if (null !== static::maxLength() && static::maxLength() < $valueLength) {
            throw InvalidStringException::maxLength($this->value, static::maxLength());
        }
    }

    public static function minLength(): ?int
    {
        return static::fixedLength();
    }

    public static function maxLength(): ?int
    {
        return static::fixedLength();
    }

    public static function fixedLength(): ?int
    {
        return null;
    }
}
