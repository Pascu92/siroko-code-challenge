<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\Product;
use App\Domain\Cart\Cart;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductInitialization(): void
    {
        $product = new Product('Product 1', 50.0, 5);

        $this->assertSame('Product 1', $product->getName());
        $this->assertSame(50.0, $product->getPrice());
        $this->assertSame(5, $product->getQuantity());
        $this->assertNull($product->getCart());
    }


    public function testSetQuantityValid(): void
    {
        $product = new Product(name: 'Product 1', price: 50, quantity: 2);
        $product->setQuantity(5);

        $this->assertSame(5, $product->getQuantity());
    }

    public function testSetQuantityThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be a positive integer');

        $product = new Product(name: 'Product 1', price: 50, quantity: 2);
        $product->setQuantity(0);
    }

    public function testSetAndGetCart(): void
    {
        $cart = $this->createMock(Cart::class);
        $product = new Product(name: 'Product 1', price: 50, quantity: 1);

        $product->setCart($cart);
        $this->assertSame($cart, $product->getCart());

        $product->setCart(null);
        $this->assertNull($product->getCart());
    }
}
