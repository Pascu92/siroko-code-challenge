<?php

namespace App\Application\Service\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;

class ConfirmPurchaseService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $cartId): void
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new CartNotFoundException('Cart not found');
        }

        $cart->confirmPurchase();
        $this->cartRepository->save($cart);
    }
}