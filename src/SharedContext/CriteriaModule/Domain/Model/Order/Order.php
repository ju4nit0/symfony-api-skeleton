<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Order;

use App\SharedContext\CriteriaModule\Domain\Model\Shared\Field;

final class Order implements BaseOrder
{
    private Field $field;
    private OrderType $orderType;

    public function __construct(Field $field, OrderType $orderType = null)
    {
        $this->field = $field;
        $this->orderType = $orderType ?? new OrderType('');
    }

    public static function deserialize(string $order): self
    {
        $order = trim($order);
        $firstChart = $order[0];

        if ('-' === $firstChart || '+' === $firstChart) {
            return new static(
                new Field(substr($order, 1)),
                new OrderType($firstChart),
            );
        }

        $orderParts = explode(' ', $order);
        return new static(
            new Field($orderParts[0]),
            new OrderType($orderParts[1] ?? ''),
        );
    }

    public function serialize(): string
    {
        return $this->field->value() . ' ' . $this->orderType->value();
    }

    public function field(): Field
    {
        return $this->field;
    }

    public function type(): OrderType
    {
        return $this->orderType;
    }

    public function hasField(string $field): bool
    {
        return $this->field->has($field);
    }
}
