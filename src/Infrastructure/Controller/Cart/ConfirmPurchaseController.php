<?php

namespace App\Infrastructure\Controller\Cart;

use App\Application\Service\Cart\ConfirmPurchaseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmPurchaseController
{
    public function __construct(
        private ConfirmPurchaseService $confirmPurchaseService
    ) {}

    #[Route('/cart/{cartId}/confirm', name: 'confirm_purchase', methods: ['POST'])]
    public function __invoke(int $cartId): JsonResponse
    {
        $this->confirmPurchaseService->execute($cartId);

        return new JsonResponse(
            ['message' => 'Purchase successful'],
            JsonResponse::HTTP_OK
        );
    }
}