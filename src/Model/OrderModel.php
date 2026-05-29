<?php

namespace App\Model;

class OrderModel
{
    private array $items;
    private float $total;

    public function __construct(array $items, float $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
