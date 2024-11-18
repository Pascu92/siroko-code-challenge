<?php

namespace App\Domain\Cart;

interface CartRepositoryInterface
{
    public function save(Cart $cart): void;
    public function findById(int $id): ?Cart;
}