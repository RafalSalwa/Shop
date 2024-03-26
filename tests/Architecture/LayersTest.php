<?php

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\BuildStep;
use PHPat\Test\PHPat;

final class LayersTest
{
    public function testClassesDoNotDependOnSecurity(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace(\Symfony\Bundle\SecurityBundle\Security::class))
            ->because('We cannot rely on Security since we are using JWT Tokens. Use ShopSecurityProviderInterface');
    }
}
