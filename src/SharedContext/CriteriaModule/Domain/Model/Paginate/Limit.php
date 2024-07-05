<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Paginate;

use App\SharedContext\CriteriaModule\Domain\Exception\Paginate\InvalidPaginateException;

class Limit
{
    private int $value;

    public function __construct(int $limit)
    {
        self::validate($limit);
        $this->value = $limit;
    }

    public static function validate(int $limit): void
    {
        if (0 > $limit) {
            throw InvalidPaginateException::limit((string)$limit);
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function isZero(): bool
    {
        return 0 === $this->value;
    }
}
