<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Contract;

use App\SharedContext\CriteriaModule\Domain\Model\Paginate\Paginate;

/**
 * @template T
 */
interface PaginateApplicator
{
    /**
     * @param T $list
     * @return T
     */
    public function __invoke(mixed $list, Paginate $paginate): mixed;
}
