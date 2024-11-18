<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\DTO\ProductDTO;
use App\Application\Service\Cart\UpdateProductQuantityService;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class UpdateProductQuantityServiceTest extends TestCase
{
    public function testExecuteUpdatesProductProperties(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $product = new Product('Product 1', 100.0, 1);
        $this->setPrivateProperty($product, 'id', 1);

        $cart->addProduct($product);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $productDTO = new ProductDTO(
            1,
            5,
            'Updated Product 1',
            150.99
        );

        $service = new UpdateProductQuantityService($cartRepository);
        $service->execute(1, $productDTO);

        $this->assertEquals('Updated Product 1', $product->getName());
        $this->assertEquals(150.99, $product->getPrice());
        $this->assertEquals(5, $product->getQuantity());
    }

    public function testExecuteUpdatesSingleProperty(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $product = new Product('Product 1', 100.0, 1);
        $this->setPrivateProperty($product, 'id', 1);

        $cart->addProduct($product);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $productDTO = new ProductDTO(
            1,
            10
        );

        $service = new UpdateProductQuantityService($cartRepository);
        $service->execute(1, $productDTO);

        $this->assertEquals(10, $product->getQuantity());
        $this->assertEquals('Product 1', $product->getName());
        $this->assertEquals(100.0, $product->getPrice());
    }

    public function testExecuteThrowsProductNotFoundException(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $productDTO = new ProductDTO(
            999,
            5
        );

        $service = new UpdateProductQuantityService($cartRepository);

        $this->expectException(ProductNotFoundException::class);
        $this->expectExceptionMessage('Product not found in cart');

        $service->execute(1, $productDTO);
    }

    public function testExecuteValidatesProductDTO(): void
    {
        $cart = new Cart();
        $this->setPrivateProperty($cart, 'id', 1);

        $product = new Product('Product 1', 100.0, 1);
        $this->setPrivateProperty($product, 'id', 1);

        $cart->addProduct($product);

        $cartRepository = $this->createMock(CartRepositoryInterface::class);
        $cartRepository->method('findById')->willReturn($cart);

        $productDTO = new ProductDTO(
            1,
            3,
            'New Product',
            -50.0
        );

        $service = new UpdateProductQuantityService($cartRepository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Product price must be a positive number.');

        $service->execute(1, $productDTO);
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
