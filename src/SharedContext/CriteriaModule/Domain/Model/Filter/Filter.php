<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Filter;


use App\SharedContext\CriteriaModule\Domain\Model\Shared\Field;
use App\SharedContext\CriteriaModule\Domain\Model\Shared\Value;

final class Filter extends BaseFilter
{
    public function __construct(
        private Field $field,
        private ComparisonOperator $operator,
        private Value $value,
    ) {
    }

    public static function deserialize(string $filterExpression): self
    {
        $operatorRegex = ComparisonOperator::regex();

        $fieldAndValue = preg_split($operatorRegex, $filterExpression);
        preg_match($operatorRegex, $filterExpression, $operators);
        $field = $fieldAndValue[0] ?? '';
        $value = $fieldAndValue[1] ?? '';

        return new static(
            new Field($field),
            new ComparisonOperator($operators[0] ?? ''),
            Value::deserialize(trim($value)),
        );
    }

    public function serialize(): string
    {
        return $this->field->value() . ' ' . $this->operator->value() . ' ' . $this->value->serialize();
    }

    public function field(): Field
    {
        return $this->field;
    }

    public function operator(): ComparisonOperator
    {
        return $this->operator;
    }

    public function value(): Value
    {
        return $this->value;
    }

    public function hasField(string $field): bool
    {
        return $this->field->has($field);
    }
}
