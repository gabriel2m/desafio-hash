<?php

namespace App\Tests\Route;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HashTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/hash/test');

        $this->assertResponseIsSuccessful();
    }
}
