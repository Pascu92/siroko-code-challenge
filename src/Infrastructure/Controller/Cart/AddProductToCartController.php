<?php

namespace App\Infrastructure\Controller\Cart;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Application\DTO\ProductDTO;
use App\Application\Service\Cart\AddProductToCartService;

class AddProductToCartController
{
    public function __construct(
        private AddProductToCartService $addProductToCartService
    ) {}

    #[Route('/cart/{cartId}/product', name: 'add_product_to_cart', methods: ['POST'])]
    public function __invoke(Request $request, int $cartId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->validateProductData($data);

        $productDTO = new ProductDTO(
            $data['quantity'],
            $data['name'] ?? '',
            $data['price'] ?? 0.0
        );

        $cart = $this->addProductToCartService->execute($cartId, $productDTO);

        return new JsonResponse([
            'cartId' => $cart->getId(),
            'products' => array_map(function ($product) {
                return [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'quantity' => $product->getQuantity(),
                ];
            }, $cart->getProducts()->toArray()),
        ]);
    }

    private function validateProductData(array $data): void
    {
        if (empty($data['name'])) {
            throw new \InvalidArgumentException('Product name cannot be empty.');
        }
        if (empty($data['price']) || $data['price'] <= 0) {
            throw new \InvalidArgumentException('Product price must be a positive number.');
        }
        if (empty($data['quantity']) || $data['quantity'] < 1) {
            throw new \InvalidArgumentException('Product quantity must be at least 1.');
        }
    }
}
