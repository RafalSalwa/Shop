<?php

declare(strict_types=1);

namespace App\Tests\Functional\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SignInTypeTest extends WebTestCase
{
    public function testSubmitValidData(): void
    {
        //        $client = self::createClient();
        //        $crawler = $client->request('GET', '/login');
        //        $client->followRedirect();
        //        $form = $crawler->selectButton('send')->form();
        //
        //        $formData = [
        //            'signInType[email]' => 'test@example.com',
        //            'signInType[password]' => 'password123',
        //        ];
        //
        //        $client->submit($form, $formData);
        //
        //        $this->assertTrue($client->getResponse()->isRedirect());
    }
}
