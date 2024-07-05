<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Filter;


use App\SharedContext\SharedModule\Domain\ValueObject\EnumValueObject;

/**
 * @method bool isAnd()
 * @method bool isOr()
 * @method static $this and ()
 * @method static $this or ()
 */
readonly class LogicalOperator extends EnumValueObject
{
    protected const AND = 'and';
    protected const OR = 'or';

    public function __construct(string $value)
    {
        $value = $this->map($value);
        parent::__construct($value);
    }

    public function equals(?LogicalOperator $logicalOperator): bool
    {
        return null !== $logicalOperator && $logicalOperator->value() === $this->value();
    }

    public static function regex(): string
    {
        return '/^(and|or|&&|\|\|)$/i';
    }

    private function map(string $value): string
    {
        $mapFields = self::mapFields();

        return $mapFields[trim($value)] ?? $value;
    }

    /**
     * @return string[]
     */
    private static function mapFields(): array
    {
        return [
            "&&" => self::AND,
            "and" => self::AND,

            "||" => self::OR,
            "or" => self::OR,
        ];
    }
}
