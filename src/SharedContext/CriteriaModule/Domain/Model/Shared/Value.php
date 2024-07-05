<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Shared;

use App\SharedContext\CriteriaModule\Domain\Service\StringHelper;

final class Value
{
    private string $value;
    private ValueType $type;

    public function __construct(string $value)
    {
        $this->value = trim($value);
        $this->type = ValueType::fromValue($value);
    }

    public static function deserialize(string $value): self
    {
        return new static($value);
    }

    public function serialize(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function type(): ValueType
    {
        return $this->type;
    }

    public function scalar(): mixed
    {
        if ($this->type->isBoolean()) {
            return $this->value === 'true';
        }

        if ($this->type->isNull()) {
            return null;
        }

        if ($this->type->isInt()) {
            return (int)$this->value;
        }

        if ($this->type->isDecimal()) {
            return (float)$this->value;
        }

        if ($this->type->isArray()) {
            return array_map(
                static function ($item): mixed {
                    return self::deserialize($item)->scalar();
                },
                StringHelper::split($this->value),
            );
        }

        return StringHelper::unquote($this->value);
    }
}
