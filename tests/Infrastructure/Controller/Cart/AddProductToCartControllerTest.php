<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddProductToCartControllerTest extends WebTestCase
{
    public function testAddProductToCartSuccess(): void
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
                'name' => 'Product 1',
                'price' => 50.0,
                'quantity' => 2,
            ])
        );

        $this->assertResponseStatusCodeSame(200);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('cartId', $data);
        $this->assertEquals($cartId, $data['cartId']);
        $this->assertArrayHasKey('products', $data);
        $this->assertCount(1, $data['products']);
        $this->assertEquals('Product 1', $data['products'][0]['name']);
        $this->assertEquals(50.0, $data['products'][0]['price']);
        $this->assertEquals(2, $data['products'][0]['quantity']);
    }

    public function testAddProductToNonExistentCart(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/cart/9999/product',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Product 1',
                'price' => 50.0,
                'quantity' => 2,
            ])
        );
        $this->assertResponseStatusCodeSame(404);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $data);
        $this->assertStringContainsString('Cart not found', $data['error']);
    }
}
