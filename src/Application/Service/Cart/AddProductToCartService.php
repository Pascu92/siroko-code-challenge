<?php

namespace App\Application\Service\Cart;

use App\Application\DTO\ProductDTO;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;

class AddProductToCartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $cartId, ProductDTO $productData): Cart
    {
        $this->validateProductDTO($productData);
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new CartNotFoundException('Cart not found');
        }

        $product = new Product(
            $productData->name,
            $productData->price,
            $productData->quantity
        );

        $cart->addProduct($product);
        $this->cartRepository->save($cart);

        return $cart;
    }

    private function validateProductDTO(ProductDTO $productData): void
    {
        if (empty($productData->name)) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }

        if ($productData->price <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than 0.');
        }

        if ($productData->quantity < 1) {
            throw new \InvalidArgumentException('Product quantity must be at least 1.');
        }
    }
}
