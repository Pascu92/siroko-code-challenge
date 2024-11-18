<?php

namespace App\Infrastructure\Controller\Cart;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\DTO\ProductDTO;
use App\Application\Service\Cart\UpdateProductQuantityService;

class UpdateProductQuantityController
{
    public function __construct(
        private UpdateProductQuantityService $updateProductQuantityService
    ) {}

    #[Route('/cart/{cartId}/product/{productId}', name: 'update_product_quantity', methods: ['PATCH'])]
    public function __invoke(Request $request, int $cartId, int $productId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(
                ['error' => 'Invalid JSON payload'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $validationError = $this->validateProductPayload($data);
        if ($validationError !== null) {
            return $validationError;
        }

        $productDTO = new ProductDTO(
            $productId,
            $data['quantity'] ?? null,
            $data['name'] ?? null,
            $data['price'] ?? null
        );

        try {
            $this->updateProductQuantityService->execute($cartId, $productDTO);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'An error occurred while updating the product.'],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return new JsonResponse(
            ['message' => 'Product updated successfully'],
            JsonResponse::HTTP_OK
        );
    }

    private function validateProductPayload(array $data): ?JsonResponse
    {
        if (empty($data['quantity']) && empty($data['name']) && empty($data['price'])) {
            return new JsonResponse(
                ['error' => 'At least one field (quantity, name, or price) must be provided.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (isset($data['name']) && strlen($data['name']) < 3) {
            return new JsonResponse(
                ['error' => 'Product name must be at least 3 characters long.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (isset($data['price']) && $data['price'] <= 0) {
            return new JsonResponse(
                ['error' => 'Product price must be a positive number.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (isset($data['quantity']) && $data['quantity'] < 1) {
            return new JsonResponse(
                ['error' => 'Product quantity must be at least 1.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        return null;
    }
}
