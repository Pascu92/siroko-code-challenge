<?php

namespace App\Tests\Infrastructure\Controller\Cart;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateCartControllerTest extends WebTestCase
{
    public function testCreateCartSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/cart');

        $this->assertResponseStatusCodeSame(201);

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertIsInt($data['id']);
    }

    public function testCreateCartInvalidMethod(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/cart');

        $this->assertResponseStatusCodeSame(405);
    }
}
