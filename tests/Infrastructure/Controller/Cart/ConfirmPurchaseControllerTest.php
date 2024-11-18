<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfirmPurchaseControllerTest extends WebTestCase
{
    public function testConfirmPurchaseSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');
        $cartId = json_decode($client->getResponse()->getContent(), true)['cartId'];

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

        $client->request('POST', "/api/cart/{$cartId}/confirm");

        $this->assertResponseStatusCodeSame(200);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertSame('confirmed', $data['status']);
    }

    public function testConfirmEmptyCart(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');
        $cartId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request('POST', "/api/cart/{$cartId}/confirm");

        $this->assertResponseStatusCodeSame(400);
    }
}
