<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Exception\InvalidEmailException;

readonly class Email extends StringValueObject
{
    protected static function exceptionClass(): ?string
    {
        return InvalidEmailException::class;
    }

    protected function doValidate(): void
    {
        parent::doValidate();
        filter_var($this->value, FILTER_VALIDATE_EMAIL) || throw InvalidEmailException::from($this->value);
    }
}
