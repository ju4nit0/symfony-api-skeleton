<?php

declare(strict_types=1);

namespace App\SharedContext\CriteriaModule\Domain\Model\Order;

final class OrderGroup implements BaseOrder
{
    /** @var array<Order> */
    private array $orders;

    public function __construct()
    {
        $this->orders = [];
    }

    public static function create(): self
    {
        return new static();
    }

    public static function deserialize(string $stringOrders): self
    {
        $orderGroup = new static();
        $orders = explode(',', $stringOrders);

        foreach ($orders as $order) {
            $orderGroup->add(Order::deserialize($order));
        }

        return $orderGroup;
    }

    public function serialize(): string
    {
        $serializes = [];
        foreach ($this->orders as $order) {
            $serializes[] = $order->serialize();
        }
        return implode(',', $serializes);
    }

    public function add(Order $order): self
    {
        $this->orders[] = $order;
        return $this;
    }

    public function count(): int
    {
        return count($this->orders);
    }

    /** @return array<Order> */
    public function orders(): array
    {
        return $this->orders;
    }

    public function get(int $index): Order
    {
        return $this->orders[$index];
    }

    public function hasField(string $field): bool
    {
        foreach ($this->orders as $order) {
            if ($order->hasField($field)) {
                return true;
            }
        }
        return false;
    }
}
