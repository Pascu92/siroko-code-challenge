<?php

namespace App\Infrastructure\Repository;

use App\Domain\Product\ProductRepositoryInterface;
use App\Domain\Product\Product;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    public function findById(int $id): ?Product
    {
        return $this->entityManager->find(Product::class, $id);
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}