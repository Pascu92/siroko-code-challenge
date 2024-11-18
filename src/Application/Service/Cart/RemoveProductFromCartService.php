<?php

namespace App\Application\Service\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Exception\ProductNotFoundException;

class RemoveProductFromCartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $cartId, int $productId): void
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new CartNotFoundException('Cart not found');
        }

        $product = $cart->findProductById($productId);
        if (!$product) {
            throw new ProductNotFoundException('Product not found');
        }
        $cart->removeProduct($product);

        $this->cartRepository->save($cart);
    }
}