<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Filter;

use App\SharedContext\CriteriaModule\Domain\Exception\Shared\FilterGroupFactory;

final class FilterGroup extends BaseFilter
{
    /** @var array<BaseFilter> */
    private array $value;

    private function __construct()
    {
        $this->value = [];
    }

    public static function create(BaseFilter $filter = null): self
    {
        $instance = new static();
        if (null !== $filter) {
            $instance->and($filter);
        }
        return $instance;
    }

    public static function deserialize(string $filter): self
    {
        return FilterGroupFactory::fromString($filter);
    }

    public function serialize(): string
    {
        $serialized = '';

        foreach ($this->value as $baseFilter) {
            $expresion = sprintf(
                $baseFilter instanceof self ? '(%s)' : '%s',
                $baseFilter->serialize(),
            );

            $serialized .= ('' === $serialized ? '' : ' ' . $baseFilter->logicalOperator()->value()) . ' ' . $expresion;
        }

        return trim($serialized);
    }

    /** @return array<BaseFilter> */
    public function filters(): array
    {
        return $this->value;
    }

    public function get(int $index): BaseFilter
    {
        return $this->value[$index];
    }

    public function and(BaseFilter $filter): self
    {
        return $this->add(LogicalOperator::and(), $filter);
    }

    public function or(BaseFilter $filter): self
    {
        return $this->add(LogicalOperator::or(), $filter);
    }

    public function hasOnlyOne(): bool
    {
        return count($this->value) === 1 && $this->value[0] instanceof Filter;
    }

    public function add(LogicalOperator $logicalOperator, BaseFilter $filter): self
    {
        $this->value[] = $filter->setLogicOperator($logicalOperator);
        return $this;
    }

    public function hasField(string $field): bool
    {
        foreach ($this->value as $item) {
            if ($item->hasField($field)) {
                return true;
            }
        }
        return false;
    }
}
