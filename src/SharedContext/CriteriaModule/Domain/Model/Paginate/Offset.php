<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Paginate;

use App\SharedContext\CriteriaModule\Domain\Exception\Paginate\InvalidPaginateException;

class Offset
{
    private int $value;

    public function __construct(int $offset)
    {
        self::validate($offset);
        $this->value = $offset;
    }

    public static function validate(int $offset): void
    {
        if (0 > $offset) {
            throw InvalidPaginateException::offset((string)$offset);
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
