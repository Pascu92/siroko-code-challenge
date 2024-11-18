<?php

namespace App\Infrastructure\Controller\Cart;

use App\Application\Service\Cart\GetTotalProductCountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetTotalProductCountController
{
    public function __construct(
        private GetTotalProductCountService $getTotalProductCountService
    ) {}

    #[Route('/cart/{cartId}/total-products', name: 'get_total_product', methods: ['GET'])]
    public function __invoke(int $cartId): JsonResponse
    {
        $totalProductCount = $this->getTotalProductCountService->execute($cartId);

        return new JsonResponse(['totalProducts' => $totalProductCount]);
    }
}