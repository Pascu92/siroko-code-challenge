<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\Service\Cart\ConfirmPurchaseService;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Product\Product;
use PHPUnit\Framework\TestCase;

class ConfirmPurchaseServiceTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private ConfirmPurchaseService $service;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->service = new ConfirmPurchaseService($this->cartRepository);
    }

    public function testExecuteConfirmsPurchase(): void
    {
        $cartId = 1;

        $cart = new Cart();
        $reflection = new \ReflectionClass($cart);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($cart, $cartId);

        $product = new Product('Product 1', 100.0, 1);
        $cart->addProduct($product);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $this->cartRepository
            ->expects($this->once())
            ->method('save')
            ->with($cart);

        $this->service->execute($cartId);

        $this->assertSame('confirmed', $cart->getStatus());
    }

    public function testExecuteThrowsCartNotFoundException(): void
    {
        $cartId = 1;
        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionMessage('Cart not found');

        $this->service->execute($cartId);
    }
}
