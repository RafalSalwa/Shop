<?php

namespace App\Tests\Architecture;

use PHPat\Test\Builder\Rule;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;

class LayersTest {

    public function test_servicesDoesNotDependOnEntities(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::inNamespace('App\Service'))
            ->shouldNotDependOn()
            ->classes(
                Selector::inNamespace('App\Entity'),
                Selector::inNamespace('App\Infrastructure'),
                Selector::classname(SuperForbiddenClass::class),
                Selector::classname('/^SomeVendor\\\.*\\\ForbiddenSubfolder\\\.*/', true)
            );
    }
}