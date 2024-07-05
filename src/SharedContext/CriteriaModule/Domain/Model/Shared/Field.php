<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Shared;

use App\SharedContext\CriteriaModule\Domain\Exception\Shared\InvalidFieldException;

class Field
{
    private string $value;

    public function __construct(string $field)
    {
        $field = trim($field);

        $this->validate($field);
        $this->value = $field;
    }

    private function validate(string $field): void
    {
        if (empty($field) || false !== strpos($field, ' ')) {
            throw InvalidFieldException::invalidField($field);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function has(string $field): bool
    {
        $shortField = strlen($field) < strlen($this->value) ? $field : $this->value;
        $longField = $shortField === $field ? $this->value : $field;

        return 0 === strpos($longField . '.', $shortField . '.');
    }

    public function equals(?Field $field): bool
    {
        return null !== $field && $field->value() === $this->value();
    }
}
