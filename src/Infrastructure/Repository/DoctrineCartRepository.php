<?php

namespace App\Infrastructure\Repository;

use App\Domain\Cart\CartRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Cart\Cart;

class DoctrineCartRepository implements CartRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    public function save(Cart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Cart
    {
        return $this->entityManager->getRepository(Cart::class)->find($id);
    }
}