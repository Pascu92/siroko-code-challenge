<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\DTO\ProductDTO;
use App\Application\Service\Cart\AddProductToCartService;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Exception\CartNotFoundException;
use PHPUnit\Framework\TestCase;

class AddProductToCartServiceTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private AddProductToCartService $service;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->service = new AddProductToCartService($this->cartRepository);
    }

    public function testExecuteSuccessfullyAddsProductToCart(): void
    {
        $cartId = 1;
        $productDTO = new ProductDTO(
            2,
            'Product 1',
            50.0
        );

        $cart = $this->createMock(Cart::class);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $cart
            ->expects($this->once())
            ->method('addProduct')
            ->with($this->callback(function ($product) use ($productDTO) {
                return $product->getName() === $productDTO->name &&
                    $product->getPrice() === $productDTO->price &&
                    $product->getQuantity() === $productDTO->quantity;
            }));

        $this->cartRepository
            ->expects($this->once())
            ->method('save')
            ->with($cart);

        $this->service->execute($cartId, $productDTO);
    }

    public function testExecuteThrowsCartNotFoundException(): void
    {
        $cartId = 1;
        $productDTO = new ProductDTO(
            2,
            'Product 1',
            50.0
        );

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->expectException(CartNotFoundException::class);
        $this->expectExceptionMessage('Cart not found');

        $this->service->execute($cartId, $productDTO);
    }
}
