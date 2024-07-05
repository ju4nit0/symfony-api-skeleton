<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Contract;

use App\SharedContext\CriteriaModule\Domain\Model\Criteria\Criteria;

/**
 * @template T
 */
interface CriteriaApplicator
{
    /**
     * @param T $list
     * @param array<string, mixed> $mapFields
     * @return T
     */
    public function __invoke(mixed $list, Criteria $criteria, array $mapFields): mixed;
}
