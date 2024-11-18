<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\Service\Cart\RemoveProductFromCartService;
use App\Domain\Cart\Cart;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Product\Product;
use App\Domain\Cart\CartRepositoryInterface;
use PHPUnit\Framework\TestCase;

class RemoveProductFromCartServiceTest extends TestCase
{
    public function testExecuteRemovesProductFromCart(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $product = new Product('Product 1', 100.0, 1);
        $this->setPrivateProperty($product, 'id', 1);

        $cart->addProduct($product);

        var_dump($cart->getProducts());

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $service = new RemoveProductFromCartService($cartRepository);
        $service->execute(1, 1);

        $this->assertCount(0, $cart->getProducts());
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

        $service = new RemoveProductFromCartService($cartRepository);
        $service->execute(1, 1);
    }
}
