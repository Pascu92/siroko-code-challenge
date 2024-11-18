<?php

namespace App\Domain\Product;

use App\Domain\Cart\Cart;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "products")]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    public ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    public string $name;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    public float $price;

    #[ORM\Column(type: "integer")]
    public int $quantity;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: "products")]
    #[ORM\JoinColumn(nullable: false)]
    public ?Cart $cart = null;

    public function __construct(string $name, float $price, int $quantity = 1, ?Cart $cart = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->setQuantity($quantity);
        $this->cart = $cart;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): void
    {
        $this->cart = $cart;
    }
}
