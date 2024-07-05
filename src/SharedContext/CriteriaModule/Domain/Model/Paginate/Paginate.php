<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Paginate;

final class Paginate
{
    private Offset $offset;
    private Limit $limit;

    public function __construct(Offset $offset = null, ?Limit $limit = null)
    {
        $this->limit = $limit ?? new Limit(0);
        $this->offset = $offset ?? new Offset(0);
    }

    public static function create(int $offset = 0, int $limit = 0): self
    {
        return new static(new Offset($offset), new Limit($limit));
    }

    public static function deserialize(string $pagination): self
    {
        preg_match_all('/\d+/', $pagination, $matches);

        $matches = $matches[0];
        $offset = (int)trim($matches[0] ?? 0);
        $limit = (int)trim($matches[1] ?? 0);

        return new static(new Offset($offset), new Limit($limit));
    }

    public static function unlimited(): self
    {
        return new static(new Offset(0), new Limit(0));
    }

    public function serialize(): string
    {
        return $this->offset->value() . ',' . $this->limit->value();
    }

    public function isUnlimited(): bool
    {
        return $this->offset->isZero() && $this->limit->isZero();
    }

    public function limit(): Limit
    {
        return $this->limit;
    }

    public function offset(): Offset
    {
        return $this->offset;
    }
}
