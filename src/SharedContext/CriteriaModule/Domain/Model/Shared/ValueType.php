<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Shared;

use App\SharedContext\CriteriaModule\Domain\Service\StringHelper;
use App\SharedContext\SharedModule\Domain\ValueObject\EnumValueObject;

/**
 * @method bool isString()
 * @method bool isInt()
 * @method bool isDecimal()
 * @method bool isArray()
 * @method bool isNull()
 * @method bool isBoolean()
 * @method static $this string()
 * @method static $this int()
 * @method static $this decimal()
 * @method static $this array()
 * @method static $this null()
 * @method static $this boolean()
 */
readonly class ValueType extends EnumValueObject
{
    protected const STRING = 'string';
    protected const INT = 'int';
    protected const DECIMAL = 'decimal';
    protected const ARRAY = 'array';
    protected const NULL = 'null';
    protected const BOOLEAN = 'boolean';

    public static function fromValue(string $value): self
    {
        if ('null' === $value) {
            return new self(self::NULL);
        }

        if (is_numeric($value)) {
            if (strpos($value, '.')) {
                return new self(self::DECIMAL);
            }
            return new self(self::INT);
        }

        if ('false' === $value || 'true' === $value) {
            return new self(self::BOOLEAN);
        }

        $parts = StringHelper::split($value);
        if (1 < count($parts)) {
            return new self(self::ARRAY);
        }

        return new self(self::STRING);
    }
}
