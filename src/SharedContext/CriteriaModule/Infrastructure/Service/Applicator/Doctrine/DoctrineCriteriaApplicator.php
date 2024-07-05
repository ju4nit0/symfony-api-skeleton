<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Infrastructure\Service\Applicator\Doctrine;

use App\SharedContext\CriteriaModule\Domain\Contract\CriteriaApplicator;
use App\SharedContext\CriteriaModule\Domain\Model\Criteria\Criteria;
use Doctrine\ORM\QueryBuilder;

/** @implements CriteriaApplicator<QueryBuilder> */
class DoctrineCriteriaApplicator implements CriteriaApplicator
{
    public function __construct(
        private DoctrineFilterApplicator $doctrineFilterApplicator,
        private DoctrineOrderApplicator $doctrineOrderApplicator,
        private DoctrinePaginateApplicator $doctrinePaginateApplicator,
    ) {
    }

    public function __invoke(mixed $list, Criteria $criteria, array $mapFields = []): mixed
    {
        if (null !== $criteria->filters()) {
            $list = $this->doctrineFilterApplicator->__invoke($list, $criteria->filters(), $mapFields);
        }

        if (null !== $criteria->orders()) {
            $list = $this->doctrineOrderApplicator->__invoke($list, $criteria->orders(), $mapFields);
        }

        if (null !== $criteria->paginate()) {
            $list = $this->doctrinePaginateApplicator->__invoke($list, $criteria->paginate());
        }
        return $list;
    }
}
