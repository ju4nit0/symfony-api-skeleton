<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Infrastructure\Service\Applicator\Doctrine;

use App\SharedContext\CriteriaModule\Domain\Contract\OrderApplicator;
use App\SharedContext\CriteriaModule\Domain\Model\Order\OrderGroup;
use Doctrine\ORM\QueryBuilder;

/** @implements OrderApplicator<QueryBuilder> */
class DoctrineOrderApplicator implements OrderApplicator
{
    public function __invoke(mixed $list, OrderGroup $orderGroup, array $mapFields): mixed
    {
        foreach ($orderGroup->orders() as $order) {
            $mapped = $mapFields[$order->field()->value()] ?? $order->field()->value();
            $list->orderBy($mapped, $order->type()->isAsc() ? 'ASC' : 'DESC');
        }

        return $list;
    }
}
