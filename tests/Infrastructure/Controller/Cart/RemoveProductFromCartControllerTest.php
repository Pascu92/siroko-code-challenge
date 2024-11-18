<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RemoveProductFromCartControllerTest extends WebTestCase
{
    public function testRemoveProductFromCartSuccess(): void
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
            'DELETE',
            "/api/cart/{$cartId}/product/1"
        );

        $this->assertResponseStatusCodeSame(200);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('products', $data);
        $this->assertCount(0, $data['products']);
    }

    public function testRemoveNonExistentProduct(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');
        $cartId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request('DELETE', "/api/cart/{$cartId}/product/9999");

        $this->assertResponseStatusCodeSame(404);
    }

    public function testRemoveProductFromNonExistentCart(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/cart/9999/product/1');

        $this->assertResponseStatusCodeSame(404);
    }
}
