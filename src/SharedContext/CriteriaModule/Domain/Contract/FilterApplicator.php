<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Contract;

use App\SharedContext\CriteriaModule\Domain\Model\Filter\Filter;
use App\SharedContext\CriteriaModule\Domain\Model\Filter\FilterGroup;

/**
 * @template T
 */
interface FilterApplicator
{
    /**
     * @param T $list
     * @param array<string, mixed> $mapFields
     * @return T
     */
    public function __invoke(mixed $list, FilterGroup|Filter|null $filter, array $mapFields): mixed;
}
