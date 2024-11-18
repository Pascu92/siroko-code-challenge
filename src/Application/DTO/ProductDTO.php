<?php

namespace App\Application\DTO;

class ProductDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $quantity = null,
        public ?string $name = null,
        public ?float $price = null
    ) {}

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
