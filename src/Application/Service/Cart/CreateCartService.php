<?php

namespace App\Application\Service\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;

class CreateCartService
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    ) {}

    public function execute(): void
    {
        $cart = new Cart();
        $this->cartRepository->save($cart);
    }
}