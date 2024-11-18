<?php

namespace App\Application\Service\Cart;

use App\Application\DTO\ProductDTO;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Product\Product;

class UpdateProductQuantityService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $cartId, ProductDTO $productDTO): void
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new CartNotFoundException('Cart not found');
        }

        $product = $cart->findProductById($productDTO->id);
        if (!$product) {
            throw new ProductNotFoundException('Product not found in cart');
        }

        $this->validateAndSetProductData($product, $productDTO);

        $this->cartRepository->save($cart);
    }

    private function validateAndSetProductData(Product $product, ProductDTO $productDTO): void
    {
        if (!empty($productDTO->name)) {
            if (strlen($productDTO->name) < 3) {
                throw new \InvalidArgumentException('Product name must be at least 3 characters long.');
            }
            $product->setName($productDTO->name);
        }

        if (!is_null($productDTO->price)) {
            if ($productDTO->price <= 0) {
                throw new \InvalidArgumentException('Product price must be a positive number.');
            }
            $product->setPrice($productDTO->price);
        }

        if (!is_null($productDTO->quantity)) {
            if ($productDTO->quantity < 1) {
                throw new \InvalidArgumentException('Product quantity must be at least 1.');
            }
            $product->setQuantity($productDTO->quantity);
        }
    }
}