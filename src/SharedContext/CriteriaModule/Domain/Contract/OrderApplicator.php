<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Contract;

use App\SharedContext\CriteriaModule\Domain\Model\Order\OrderGroup;

/**
 * @template T
 */
interface OrderApplicator
{
    /**
     * @param T $list
     * @param array<string, mixed> $mapFields
     * @return T
     */
    public function __invoke(mixed $list, OrderGroup $orderGroup, array $mapFields): mixed;
}
