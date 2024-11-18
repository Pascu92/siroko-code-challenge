<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\DoctrineCartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class DoctrineCartRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineCartRepository $repository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = new DoctrineCartRepository($this->entityManager);
    }

    public function testFindByIdReturnsCart(): void
    {
        $cartId = 1;
        $mockCart = $this->createMock(Cart::class);

        $entityRepository = $this->createMock(EntityRepository::class);
        $entityRepository
            ->expects($this->once())
            ->method('find')
            ->with($cartId)
            ->willReturn($mockCart);
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(Cart::class)
            ->willReturn($entityRepository);

        $result = $this->repository->findById($cartId);

        $this->assertSame($mockCart, $result);
    }



    public function testFindByIdReturnsNullIfCartNotFound(): void
    {
        $cartId = 1;

        $entityRepository = $this->createMock(EntityRepository::class);
        $entityRepository->method('find')->with($cartId)->willReturn(null);

        $this->entityManager
            ->method('getRepository')
            ->with(Cart::class)
            ->willReturn($entityRepository);

        $result = $this->repository->findById($cartId);

        $this->assertNull($result);
    }

    public function testSavePersistsAndFlushesCart(): void
    {
        $cart = new Cart();

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($cart);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository->save($cart);
    }
}
