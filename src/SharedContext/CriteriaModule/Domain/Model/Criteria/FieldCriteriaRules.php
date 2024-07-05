<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Criteria;

use App\SharedContext\CriteriaModule\Domain\Exception\Criteria\FieldCriteriaRuleException;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\ComparisonOperator;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Order\Order;
use App\SharedContext\CriteriaModule\Domain\Model\Shared\Field;

final class FieldCriteriaRules
{
    private Field $field;
    private bool $filterable;
    private bool $sortable;
    /** @var array<ComparisonOperator> */
    private array $comparisonOperators;

    public function __construct(Field $field)
    {
        $this->field = $field;
        $this->comparisonOperators = [];
        $this->sortable = true;
        $this->filterable = true;
    }

    public static function create(string $field): self
    {
        return new static(new Field($field));
    }

    public function field(): Field
    {
        return $this->field;
    }

    /**
     * @return array<ComparisonOperator>
     */
    public function comparisonOperators(): array
    {
        return $this->comparisonOperators;
    }

    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function setFilterable(bool $isFilterable): self
    {
        $this->filterable = $isFilterable;
        return $this;
    }

    public function setSortable(bool $isSortable): self
    {
        $this->sortable = $isSortable;
        return $this;
    }

    public function setComparisonOperators(ComparisonOperator ...$comparisonOperators): self
    {
        $this->comparisonOperators = $comparisonOperators;
        return $this;
    }

    public function assertFilter(Filter $filter): void
    {
        self::assertRuleForField($filter->field());

        if (false === $this->isFilterable()) {
            throw FieldCriteriaRuleException::ruleViolation($filter->serialize());
        }

        if (empty($this->comparisonOperators)) {
            return;
        }

        foreach ($this->comparisonOperators as $comparisonOperator) {
            if ($filter->operator()->equals($comparisonOperator)) {
                return;
            }
        }

        throw FieldCriteriaRuleException::ruleViolation($filter->serialize());
    }

    public function assertOrder(Order $order): void
    {
        $this->assertRuleForField($order->field());
        if (false === $this->isSortable()) {
            throw FieldCriteriaRuleException::ruleViolation($order->serialize());
        }
    }

    private function assertRuleForField(Field $field): void
    {
        if (!$this->field->equals($field)) {
            throw FieldCriteriaRuleException::forAssertField(
                'Invalid rule ' . $this->field->value() . ' for ' . $field->value(),
            );
        }
    }
}
