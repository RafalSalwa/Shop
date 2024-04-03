<?php

declare(strict_types=1);

namespace App\Tests\Architecture;

use PHPat\Selector\Selector;
use PHPat\Test\Builder\BuildStep;
use PHPat\Test\PHPat;
use Symfony\Bundle\SecurityBundle\Security;

final class LayersTest
{
    public function testClassesDoNotDependOnSecurity(): BuildStep
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App'))
            ->shouldNotDependOn()
            ->classes(Selector::inNamespace(Security::class))
            ->because('We cannot rely on Security since we are using JWT Tokens. Use ShopSecurityProviderInterface');
    }
}
