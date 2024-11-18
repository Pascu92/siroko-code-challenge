<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\Service\Cart\GetTotalProductCountService;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class GetTotalProductCountServiceTest extends TestCase
{
    public function testExecuteReturnsTotalProductCount(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $product1 = new Product('Product 1', 100.0, 2);
        $this->setPrivateProperty($product1, 'id', 1);

        $product2 = new Product('Product 2', 50.0, 3);
        $this->setPrivateProperty($product2, 'id', 2);

        $cart->addProduct($product1);
        $cart->addProduct($product2);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $service = new GetTotalProductCountService($cartRepository);

        $this->assertEquals(5, $service->execute(1));
    }




    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public function testExecuteThrowsCartNotFoundException()
    {
        $this->expectException(CartNotFoundException::class);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn(null);

        $service = new GetTotalProductCountService($cartRepository);
        $service->execute(1);
    }
}
