<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class E2eTest extends PantherTestCase
{
    public function testMyApp(): void
    {
        $client = static::createPantherClient(); // Your app is automatically started using the built-in web server
        $client->request('GET', '/mypage');

        // Use any PHPUnit assertion, including the ones provided by Symfony
        $this->assertPageTitleContains('My Title');
        $this->assertSelectorTextContains('#main', 'My body');

        // Or the one provided by Panther
        $this->assertSelectorIsEnabled('.search');
        $this->assertSelectorIsDisabled('[type="submit"]');
        $this->assertSelectorIsVisible('.errors');
        $this->assertSelectorIsNotVisible('.loading');
        $this->assertSelectorAttributeContains('.price', 'data-old-price', '42');
        $this->assertSelectorAttributeNotContains('.price', 'data-old-price', '36');

        // Use waitForX methods to wait until some asynchronous process finish
        $client->waitFor('.popin'); // wait for element to be attached to the DOM
        $client->waitForStaleness('.popin'); // wait for element to be removed from the DOM
        $client->waitForVisibility('.loader'); // wait for element of the DOM to become visible
        $client->waitForInvisibility('.loader'); // wait for element of the DOM to become hidden
        $client->waitForElementToContain('.total', '25 €'); // wait for text to be inserted in the element content
        $client->waitForElementToNotContain('.promotion', '5%'); // wait for text to be removed from the element content
        $client->waitForEnabled('[type="submit"]'); // wait for the button to become enabled
        $client->waitForDisabled('[type="submit"]'); // wait for  the button to become disabled
        $client->waitForAttributeToContain('.price', 'data-old-price', '25 €'); // wait for the attribute to contain content
        $client->waitForAttributeToNotContain('.price', 'data-old-price', '25 €'); // wait for the attribute to not contain content

        // Let's predict the future
        $this->assertSelectorWillExist('.popin'); // element will be attached to the DOM
        $this->assertSelectorWillNotExist('.popin'); // element will be removed from the DOM
        $this->assertSelectorWillBeVisible('.loader'); // element will be visible
        $this->assertSelectorWillNotBeVisible('.loader'); // element will not be visible
        $this->assertSelectorWillContain('.total', '€25'); // text will be inserted in the element content
        $this->assertSelectorWillNotContain('.promotion', '5%'); // text will be removed from the element content
        $this->assertSelectorWillBeEnabled('[type="submit"]'); // button will be enabled
        $this->assertSelectorWillBeDisabled('[type="submit"]'); // button will be disabled
        $this->assertSelectorAttributeWillContain('.price', 'data-old-price', '€25'); // attribute will contain content
        $this->assertSelectorAttributeWillNotContain('.price', 'data-old-price', '€25'); // attribute will not contain content
    }
}