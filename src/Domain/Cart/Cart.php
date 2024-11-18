<?php

namespace App\Domain\Cart;

use App\Domain\Product\Product;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: "carts")]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $status = 'pending';

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "cart", cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function addProduct(Product $product): void
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCart($this);
        }
    }

    public function removeProduct(Product $product): void
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->setCart(null);
        }
    }

    public function findProductById(int $productId): ?Product
    {
        foreach ($this->products as $product) {
            if ($product->getId() === $productId) {
                return $product;
            }
        }
        return null;
    }

    public function getTotalQuantity(): int
    {
        $totalQuantity = 0;
        foreach ($this->products as $product) {
            $totalQuantity += $product->getQuantity();
        }
        return $totalQuantity;
    }

    public function getTotalProductCount(): int
    {
        return $this->products->count();
    }

    public function updateProductQuantity(string $productId, int $quantity): void
    {
        $existingProduct = $this->findProductById($productId);
        if ($existingProduct !== null) {
            $existingProduct->setQuantity($quantity);
        }
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function confirmPurchase(): void
    {
        if ($this->products->isEmpty()) {
            throw new \DomainException('Cannot confirm purchase for an empty cart.');
        }

        $this->status = 'confirmed';
    }
}
