<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Criteria;

use App\SharedContext\CriteriaModule\Domain\Exception\Criteria\FieldCriteriaRuleException;
use App\SharedContext\CriteriaModule\Domain\Exception\Filter\InvalidFilterClassException;
use App\SharedContext\CriteriaModule\Domain\Exception\Order\InvalidOrderClassException;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\BaseFilter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\FilterGroup;
use App\SharedContext\CriteriaModule\Domain\Model\Order\BaseOrder;
use App\SharedContext\CriteriaModule\Domain\Model\Order\Order;
use App\SharedContext\CriteriaModule\Domain\Model\Order\OrderGroup;

class FieldCriteriaRulesGroup
{
    /** @var array<string, ?FieldCriteriaRules> */
    private array $matches = [];
    /** @var array<FieldCriteriaRules> */
    private array $rules;

    public function __construct(FieldCriteriaRules ...$rules)
    {
        $this->validate(...$rules);
        $this->rules = $rules;
    }

    /** @return array<FieldCriteriaRules> */
    public function rules(): array
    {
        return $this->rules;
    }

    public function hasRules(): bool
    {
        return !empty($this->rules);
    }

    public function assertRulesOfFilter(BaseFilter $filter): void
    {
        if (false === $this->hasRules()) {
            return;
        }

        match (true) {
            $filter instanceof FilterGroup => self::assertRulesOfFilterGroup($filter),
            $filter instanceof Filter => self::assertRulesOfSingleFilter($filter),
            default => throw InvalidFilterClassException::invalidFilterClass($filter),
        };
    }

    public function assertRulesOfOrder(BaseOrder $order): void
    {
        if (false === $this->hasRules()) {
            return;
        }

        match (true) {
            $order instanceof OrderGroup => self::assertRulesOfOrderGroup($order),
            $order instanceof Order => self::assertRulesOfSingleOrder($order),
            default => throw InvalidOrderClassException::invalidOrderClass($order),
        };
    }

    private function validate(FieldCriteriaRules ...$rules): void
    {
        $fields = array_map(
            static function (FieldCriteriaRules $rule) {
                return $rule->field()->value();
            },
            $rules,
        );

        $fieldsDuplicated = array_keys(
            array_filter(
                array_count_values($fields),
                static function ($counted) {
                    return $counted > 1;
                },
            ),
        );

        if (!empty($fieldsDuplicated)) {
            throw FieldCriteriaRuleException::rulesRepeated(implode(', ', $fieldsDuplicated));
        }
    }

    private function assertRulesOfFilterGroup(FilterGroup $filterGroup): void
    {
        /** @var Filter $filterElement */
        foreach ($filterGroup->filters() as $filterElement) {
            self::assertRulesOfFilter($filterElement);
        }
    }

    private function assertRulesOfSingleFilter(Filter $filter): void
    {
        $rule = $this->getApplyRuleByField($filter->field()->value());

        if (null === $rule) {
            throw FieldCriteriaRuleException::forAssertField($filter->serialize());
        }

        $rule->assertFilter($filter);
    }

    private function assertRulesOfOrderGroup(OrderGroup $orderGroup): void
    {
        foreach ($orderGroup->orders() as $orderElement) {
            self::assertRulesOfOrder($orderElement);
        }
    }

    private function assertRulesOfSingleOrder(Order $order): void
    {
        $rule = $this->getApplyRuleByField($order->field()->value());

        if (null === $rule) {
            throw FieldCriteriaRuleException::forAssertField($order->serialize());
        }

        $rule->assertOrder($order);
    }

    private function getApplyRuleByField(string $field): ?FieldCriteriaRules
    {
        if (isset($this->matches[$field])) {
            return $this->matches[$field];
        }

        $tmpRule = null;
        $rate = 0;
        foreach ($this->rules as $rule) {
            if ($rule->field()->has($field)) {
                $length = strlen($field);
                if ($rate < $length) {
                    $rate = $length;
                    $tmpRule = $rule;
                }
            }
        }

        $this->matches[$field] = $tmpRule;
        return $tmpRule;
    }
}
