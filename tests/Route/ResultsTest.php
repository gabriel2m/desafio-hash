<?php

namespace App\Tests\Route;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResultsTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $client = static::createClient();

        $client->request('GET', '/results');

        $this->assertResponseIsSuccessful();
    }
}
