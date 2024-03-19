<?php

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class FirstCest
{
    public function _before(AcceptanceTester $acceptanceTester)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $acceptanceTester)
    {
    }

    public function frontpageWorks(AcceptanceTester $acceptanceTester): void
    {
        $acceptanceTester->amOnPage('/login');
        $acceptanceTester->see('Login');
    }
}
