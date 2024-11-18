<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetTotalProductCountControllerTest extends WebTestCase
{
    public function testGetTotalProductCountSuccess(): void
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
            'POST',
            "/api/cart/{$cartId}/product",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'id' => 2,
                'name' => 'Product 2',
                'price' => 29.99,
                'quantity' => 3,
            ])
        );

        $client->request('GET', "/api/cart/{$cartId}/total-products");

        $this->assertResponseStatusCodeSame(200);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame(5, $data['total']);
    }

    public function testGetTotalProductCountForNonExistentCart(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/cart/9999/total-products');

        $this->assertResponseStatusCodeSame(404);
    }
}
