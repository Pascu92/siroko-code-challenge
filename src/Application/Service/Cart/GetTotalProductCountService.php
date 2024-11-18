<?php

namespace App\Application\Service\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;

class GetTotalProductCountService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(int $cartId): int
    {
        $cart = $this->cartRepository->findById($cartId);
        if (!$cart) {
            throw new CartNotFoundException('Cart not found');
        }

        return $cart->getTotalProductCount();
    }
}