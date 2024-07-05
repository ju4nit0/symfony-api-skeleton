<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Criteria;


use App\SharedContext\CriteriaModule\Domain\Exception\Filter\InvalidFilterClassException;
use App\SharedContext\CriteriaModule\Domain\Exception\Order\InvalidOrderClassException;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\BaseFilter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\FilterGroup;
use App\SharedContext\CriteriaModule\Domain\Model\Order\BaseOrder;
use App\SharedContext\CriteriaModule\Domain\Model\Order\Order;
use App\SharedContext\CriteriaModule\Domain\Model\Order\OrderGroup;
use App\SharedContext\CriteriaModule\Domain\Model\Paginate\Paginate;

class Criteria
{
    /** @var array<FieldCriteriaRulesGroup> */
    private static array $rulesGroup = [];

    private ?FilterGroup $filterGroup;
    private ?OrderGroup $orderGroup;
    private Paginate $paginate;

    final private function __construct(
        FilterGroup $filterGroup = null,
        OrderGroup $orderGroup = null,
        Paginate $paginate = null,
    ) {
        $this->validate($filterGroup, $orderGroup);

        $this->filterGroup = $filterGroup;
        $this->orderGroup = $orderGroup;
        $this->paginate = $paginate ?? Paginate::unlimited();
    }

    public static function create(
        FilterGroup $filterGroup = null,
        OrderGroup $orderGroup = null,
        Paginate $paginate = null,
    ): self {
        return new static(
            $filterGroup,
            $orderGroup,
            $paginate,
        );
    }

    private function validate(?FilterGroup $filterGroup, ?OrderGroup $orderGroup): void
    {
        if (null !== $filterGroup) {
            self::rulesGroup()->assertRulesOfFilter($filterGroup);
        }
        if (null !== $orderGroup) {
            self::rulesGroup()->assertRulesOfOrder($orderGroup);
        }
    }

    public static function rulesGroup(): FieldCriteriaRulesGroup
    {
        if (!isset(self::$rulesGroup[static::class])) {
            self::$rulesGroup[static::class] = new FieldCriteriaRulesGroup(...static::createRules());
        }

        return self::$rulesGroup[static::class];
    }

    /** @return array<FieldCriteriaRules> */
    protected static function createRules(): array
    {
        return [];
    }

    public function hasField(string $field): bool
    {
        return
            (null !== $this->filterGroup && $this->filterGroup->hasField($field))
            || (null !== $this->orderGroup && $this->orderGroup->hasField($field));
    }

    public function filters(): ?FilterGroup
    {
        return $this->filterGroup;
    }

    public function orders(): ?OrderGroup
    {
        return $this->orderGroup;
    }

    public function paginate(): Paginate
    {
        return $this->paginate;
    }

    public function setFilter(?BaseFilter $filter): self
    {
        if (null === $filter) {
            $this->filterGroup = $filter;
            return $this;
        }

        return match (true) {
            $filter instanceof FilterGroup => $this->setFilterGroup($filter),
            $filter instanceof Filter => $this->setSingleFilter($filter),
            default => throw InvalidFilterClassException::invalidFilterClass($filter),
        };
    }

    public function setOrder(?BaseOrder $order): self
    {
        if (null === $order) {
            $this->orderGroup = $order;
            return $this;
        }

        return match (true) {
            $order instanceof OrderGroup => $this->setOrderGroup($order),
            $order instanceof Order => $this->setSingleOrder($order),
            default => throw InvalidOrderClassException::invalidOrderClass($order),
        };
    }

    public function setPaginate(?Paginate $paginate): self
    {
        if (is_null($paginate)) {
            $paginate = Paginate::unlimited();
        }

        $this->paginate = $paginate;
        return $this;
    }

    private function setFilterGroup(FilterGroup $filterGroup): self
    {
        self::rulesGroup()->assertRulesOfFilter($filterGroup);

        $this->filterGroup = $filterGroup;
        return $this;
    }

    private function setSingleFilter(Filter $filter): self
    {
        $filterGroup = FilterGroup::create($filter);

        return $this->setFilterGroup($filterGroup);
    }

    private function setOrderGroup(OrderGroup $orderGroup): self
    {
        self::rulesGroup()->assertRulesOfOrder($orderGroup);

        $this->orderGroup = $orderGroup;

        return $this;
    }

    private function setSingleOrder(Order $order): self
    {
        $orderGroup = OrderGroup::create()->add($order);

        return $this->setOrderGroup($orderGroup);
    }
}
