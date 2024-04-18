<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Controller\IndexController;
use App\Controller\LoginController;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: LoginController::class)]
#[CoversClass(className: IndexController::class)]
final class LoginControllerTestCaseTest extends WebTestCase
{
    public function testResponses(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = self::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/login');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'RsShop');

        $this->assertSame(0, $crawler->filter('html:contains("RsShop")')->count());
    }
}
