<?php

declare(strict_types=1);

namespace Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CartApiTest extends WebTestCase
{
    public function testSomething(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = self::createClient();

        // Request a specific page
        $client->request('GET', '/');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Shop');
    }
}
