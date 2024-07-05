<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Filter;

abstract class BaseFilter
{
    private LogicalOperator $logicalOperator;

    public function logicalOperator(): LogicalOperator
    {
        return $this->logicalOperator ?? LogicalOperator::and();
    }

    protected function setLogicOperator(LogicalOperator $logicalOperator): self
    {
        $this->logicalOperator = $logicalOperator;
        return $this;
    }

    abstract public function hasField(string $field): bool;

    abstract public function serialize(): string;
}
