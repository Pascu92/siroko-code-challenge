<?php

namespace App\Tests\Infrastructure\Repository;

use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
use App\Infrastructure\Repository\DoctrineProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineProductRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private DoctrineProductRepository $productRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->productRepository = new DoctrineProductRepository($this->entityManager);
    }

    public function testFindByIdReturnsProduct(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();


        $product = new Product('Test Product', 99.99, 10, $cart);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $retrievedProduct = $this->productRepository->findById($product->getId());

        $this->assertNotNull($retrievedProduct);
        $this->assertInstanceOf(Product::class, $retrievedProduct);
        $this->assertSame('Test Product', $retrievedProduct->getName());
        $this->assertSame(99.99, $retrievedProduct->getPrice());
        $this->assertSame(10, $retrievedProduct->getQuantity());
    }

    public function testFindByIdReturnsNullIfProductNotFound(): void
    {
        $retrievedProduct = $this->productRepository->findById(999);
        $this->assertNull($retrievedProduct);
    }

    public function testSavePersistsProduct(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();


        $product = new Product('Product 1', 50.0, 5, $cart);
        $this->productRepository->save($product);
        $retrievedProduct = $this->productRepository->findById($product->getId());

        $this->assertNotNull($retrievedProduct);
        $this->assertSame('Product 1', $retrievedProduct->getName());
        $this->assertSame(50.0, $retrievedProduct->getPrice());
        $this->assertSame(5, $retrievedProduct->getQuantity());
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
