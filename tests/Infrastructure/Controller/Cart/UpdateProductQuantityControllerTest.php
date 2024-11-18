<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdateProductQuantityControllerTest extends WebTestCase
{
    public function testUpdateProductQuantitySuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');
        $cartId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request(
            'POST',
            "/api/cart/{$cartId}/product",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => 1,
                'name' => 'Product 1',
                'price' => 50,
                'quantity' => 2,
            ])
        );

        $client->request(
            'PUT',
            "/api/cart/{$cartId}/product/1",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 5])
        );

        $this->assertResponseStatusCodeSame(200);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(5, $data['products'][0]['quantity']);
    }

    public function testUpdateQuantityForNonExistentProduct(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');
        $cartId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request(
            'PUT',
            "/api/cart/{$cartId}/product/9999",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['quantity' => 5])
        );

        $this->assertResponseStatusCodeSame(404);
    }
}
