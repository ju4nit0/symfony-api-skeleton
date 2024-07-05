<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Filter;

use App\SharedContext\SharedModule\Domain\ValueObject\EnumValueObject;

/**
 * @method bool isEqual()
 * @method bool isNotEqual()
 * @method bool isGreater()
 * @method bool isGreaterOrEqual()
 * @method bool isLess()
 * @method bool isLessOrEqual()
 * @method bool isIn()
 * @method bool isNotIn()
 * @method bool isLike()
 * @method bool isNotLike()
 * @method static $this equal()
 * @method static $this notEqual()
 * @method static $this greater()
 * @method static $this greaterOrEqual()
 * @method static $this less()
 * @method static $this lessOrEqual()
 * @method static $this in()
 * @method static $this notIn()
 * @method static $this like()
 * @method static $this notLike()
 */
readonly class ComparisonOperator extends EnumValueObject
{
    protected const EQUAL = 'eq';
    protected const NOT_EQUAL = 'ne';
    protected const GREATER = 'gt';
    protected const GREATER_OR_EQUAL = 'ge';
    protected const LESS = 'lt';
    protected const LESS_OR_EQUAL = 'le';
    protected const IN = 'in';
    protected const NOT_IN = "not in";
    protected const LIKE = 'like';
    protected const NOT_LIKE = 'not like';

    public function __construct(string $value)
    {
        $value = $this->map($value);
        parent::__construct($value);
    }

    public function equals(?ComparisonOperator $comparisonOperator): bool
    {
        if (null === $comparisonOperator) {
            return false;
        }

        return $this->value() === $comparisonOperator->value();
    }

    public static function regex(): string
    {
        $operators = array_keys(self::mapFields());
        usort(
            $operators,
            static function ($a, $b) {
                return strlen($b) <=> strlen($a);
            },
        );

        return '/ ' . str_replace(' ', '\s+', implode(' | ', $operators)) . ' /i';
    }

    private function map(string $value): string
    {
        return self::mapFields()[trim($value)] ?? $value;
    }

    /**
     * @return string[]
     */
    private static function mapFields(): array
    {
        return [
            "=" => self::EQUAL,
            "eq" => self::EQUAL,
            "is" => self::EQUAL,

            "!=" => self::NOT_EQUAL,
            "<>" => self::NOT_EQUAL,
            "ne" => self::NOT_EQUAL,
            "neq" => self::NOT_EQUAL,
            "is not" => self::NOT_EQUAL,

            "gt" => self::GREATER,
            ">" => self::GREATER,

            ">=" => self::GREATER_OR_EQUAL,
            "ge" => self::GREATER_OR_EQUAL,
            "gte" => self::GREATER_OR_EQUAL,

            "<" => self::LESS,
            "lt" => self::LESS,

            "<=" => self::LESS_OR_EQUAL,
            "le" => self::LESS_OR_EQUAL,
            "lte" => self::LESS_OR_EQUAL,

            "in" => self::IN,

            "not in" => self::NOT_IN,

            "like" => self::LIKE,
            "contains" => self::LIKE,

            "not like" => self::NOT_LIKE,
        ];
    }
}
