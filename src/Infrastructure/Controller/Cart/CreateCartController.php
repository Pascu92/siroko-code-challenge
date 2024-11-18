<?php

namespace App\Infrastructure\Controller\Cart;

use App\Application\Service\Cart\CreateCartService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CreateCartController
{
    public function __construct(
        private CreateCartService $createCartService
    ) {}

    #[Route('/cart', name: 'create_cart', methods: ['POST'])]
    public function __invoke(): JsonResponse
    {
        $this->createCartService->execute();

        return new JsonResponse(
            ['Cart Created'],
            JsonResponse::HTTP_CREATED
        );
    }
}