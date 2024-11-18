<?php

namespace App\Tests\Application\Service\Cart;

use App\Application\Service\Cart\CreateCartService;
use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateCartServiceTest extends TestCase
{
    public function testExecuteCreatesNewCart()
    {
        $cartRepository = $this->createMock(CartRepositoryInterface::class);

        $cartRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Cart::class));

        $service = new CreateCartService($cartRepository);
        $service->execute();

        $this->assertTrue(true);
    }
}
