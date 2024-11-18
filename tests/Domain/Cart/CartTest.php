<?php

namespace App\Tests\Domain\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testAddProduct()
    {
        $cart = new Cart();
        $product = new Product('product 1', 50, 1);

        $cart->addProduct($product);

        $this->assertCount(1, $cart->getProducts());
        $this->assertSame($product, $cart->getProducts()->first());
    }

    public function testRemoveProduct()
    {
        $cart = new Cart();
        $product = new Product('product 1', 50, 1);

        $cart->addProduct($product);
        $cart->removeProduct($product);

        $this->assertCount(0, $cart->getProducts());
    }

    public function testFindProductById(): void
    {
        $cart = new Cart();
        $product = new Product('Test Product', 100.0, 1);

        $reflection = new \ReflectionClass($product);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($product, '1');

        $cart->addProduct($product);

        $foundProduct = $cart->findProductById('1');
        $this->assertSame($product, $foundProduct);

        $nonExistentProduct = $cart->findProductById('non-id');
        $this->assertNull($nonExistentProduct);
    }

    public function testConfirmPurchaseWithEmptyCart()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot confirm purchase for an empty cart.');

        $cart = new Cart();
        $cart->confirmPurchase();
    }

    public function testConfirmPurchaseWithProducts()
    {
        $cart = new Cart();
        $product = new Product('Product 1', 50, 1);

        $cart->addProduct($product);
        $cart->confirmPurchase();

        $this->assertEquals('confirmed', $cart->getStatus());
    }

    public function testGetTotalProductCount()
    {
        $cart = new Cart();
        $cart->addProduct(new Product('Product 1', 50, 1));
        $cart->addProduct(new Product('Product 2', 60, 1));

        $this->assertEquals(2, $cart->getTotalProductCount());
    }

    public function testGetTotalQuantity()
    {
        $cart = new Cart();
        $cart->addProduct(new Product('Product 1', 50, 2));
        $cart->addProduct(new Product('Product 2', 60, 3));

        $this->assertEquals(5, $cart->getTotalQuantity());
    }
}
