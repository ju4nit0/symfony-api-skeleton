<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Infrastructure\Service\Applicator\Doctrine;

use App\SharedContext\CriteriaModule\Domain\Contract\PaginateApplicator;
use App\SharedContext\CriteriaModule\Domain\Model\Paginate\Paginate;
use Doctrine\ORM\QueryBuilder;

/** @implements PaginateApplicator<QueryBuilder> */
class DoctrinePaginateApplicator implements PaginateApplicator
{
    public function __invoke(mixed $list, Paginate $paginate): mixed
    {
        if (false === $paginate->offset()->isZero()) {
            $list->setFirstResult($paginate->offset()->value());
        }

        if (false === $paginate->limit()->isZero()) {
            $list->setMaxResults($paginate->limit()->value());
        }

        return $list;
    }
}
