<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Infrastructure\Service\Applicator\Doctrine;

use App\SharedContext\CriteriaModule\Domain\Contract\FilterApplicator;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\BaseFilter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\FilterGroup;
use App\SharedContext\SharedModule\Domain\Exception\InvalidEnumException;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;

/** @implements FilterApplicator<QueryBuilder> */
class DoctrineFilterApplicator implements FilterApplicator
{
    public function __construct(
        private int $value = 0,
    ) {
    }

    public function __invoke(mixed $list, FilterGroup|Filter|null $filter, array $mapFields): mixed
    {
        if (is_null($filter)) {
            return $list;
        }

        $expr = $this->createExpr($list, $filter, $mapFields);
        $list->andWhere($expr);

        return $list;
    }

    /**
     * @param array<string, mixed> $mapFields
     */
    private function createExpr(
        QueryBuilder $builder,
        BaseFilter $filter,
        array $mapFields,
    ): Composite|Comparison|Func|string {
        return match (true) {
            $filter instanceof FilterGroup => $this->createGroupExpr($builder, $filter, $mapFields),
            $filter instanceof Filter => $this->createComparisonExpr($builder, $filter, $mapFields),
            default => throw new RuntimeException(),
        };
    }

    /**
     * @param array<string, mixed> $mapFields
     */
    private function createGroupExpr(
        QueryBuilder $builder,
        FilterGroup $filterGroup,
        array $mapFields,
    ): Composite {
        $logical = $filterGroup->logicalOperator();
        $expr = $logical->isAnd() ? $builder->expr()->andX() : $builder->expr()->orX();

        foreach ($filterGroup->filters() as $filter) {
            $expression = $this->createExpr($builder, $filter, $mapFields);

            if (!$logical->equals($filter->logicalOperator())) {
                $logical = $filter->logicalOperator();
                $expr = $logical->isAnd()
                    ? $builder->expr()->andX($expr)
                    : $builder->expr()->orX($expr);
            }

            $expr->add($expression);
        }

        return $expr;
    }

    /**
     * @param array<string, mixed> $mapFields
     */
    private function createComparisonExpr(
        QueryBuilder $builder,
        Filter $filter,
        array $mapFields,
    ): Comparison|Func|string {
        $mapped = $mapFields[$filter->field()->value()] ?? $filter->field()->value();

        if ($filter->value()->type()->isNull()) {
            return $filter->operator()->isEqual()
                ? $builder->expr()->isNull($mapped)
                : $builder->expr()->isNotNull($mapped);
        }

        $valueAlias = $this->createValueAlias();
        $builder->setParameter($valueAlias, $filter->value()->scalar());

        $valueAlias = ':' . $valueAlias;

        return match (true) {
            $filter->operator()->isEqual() => $builder->expr()->eq($mapped, $valueAlias),
            $filter->operator()->isNotEqual() => $builder->expr()->neq($mapped, $valueAlias),
            $filter->operator()->isGreater() => $builder->expr()->gt($mapped, $valueAlias),
            $filter->operator()->isGreaterOrEqual() => $builder->expr()->gte($mapped, $valueAlias),
            $filter->operator()->isLess() => $builder->expr()->lt($mapped, $valueAlias),
            $filter->operator()->isLessOrEqual() => $builder->expr()->lte($mapped, $valueAlias),
            $filter->operator()->isIn() => $builder->expr()->in($mapped, $valueAlias),
            $filter->operator()->isNotIn() => $builder->expr()->notIn($mapped, $valueAlias),
            $filter->operator()->isLike() => $builder->expr()->like($mapped, $valueAlias),
            $filter->operator()->isNotLike() => $builder->expr()->notLike($mapped, $valueAlias),
            default => throw InvalidEnumException::notValidValue($filter->operator()->value(), []),
        };
    }

    private function createValueAlias(): string
    {
        return 'value' . ++$this->value;
    }
}
