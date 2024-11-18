<?php

namespace App\Infrastructure\Controller\Cart;

use App\Application\Service\Cart\RemoveProductFromCartService;
use App\Domain\Exception\CartNotFoundException;
use App\Domain\Exception\ProductNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RemoveProductFromCartController
{
    public function __construct(
        private RemoveProductFromCartService $removeProductFromCartService
    ) {}

    #[Route('/cart/{cartId}/product/{productId}', name: 'remove_product_from_cart', methods: ['DELETE'])]
    public function __invoke(int $cartId, int $productId): JsonResponse
    {
        try {
            $this->removeProductFromCartService->execute($cartId, $productId);

            return new JsonResponse(['message' => 'Product removed successfully'], JsonResponse::HTTP_OK);
        } catch (CartNotFoundException $e) {
            return new JsonResponse(['error' => 'Cart not found'], JsonResponse::HTTP_NOT_FOUND);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => 'Product not found'], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}