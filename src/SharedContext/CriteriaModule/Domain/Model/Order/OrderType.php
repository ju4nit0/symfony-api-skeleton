<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Order;

use App\SharedContext\SharedModule\Domain\ValueObject\EnumValueObject;

/**
 * @method bool isAsc()
 * @method bool isDesc()
 * @method static $this asc()
 * @method static $this desc()
 */
readonly class OrderType extends EnumValueObject
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    private const MAP = [
        'asc' => self::ASC,
        '+' => self::ASC,
        '' => self::ASC,

        'desc' => self::DESC,
        '-' => self::DESC,
    ];

    public function __construct(string $orderType)
    {
        $orderType = $this->map($orderType);
        parent::__construct($orderType);
    }

    /**
     * @return string[]|int[]
     */
    protected static function constants(): array
    {
        $constants = parent::constants();

        return array_filter(
            $constants,
            function (mixed $item) {
                return !is_array($item);
            },
        );
    }

    private function map(string $value): string
    {
        return self::MAP[trim($value)] ?? $value;
    }
}
