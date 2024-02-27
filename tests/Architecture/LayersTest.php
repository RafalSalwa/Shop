<?php

namespace App\Tests\Architecture;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;

final class MyFirstTest
{
    public function test_domain_does_not_depend_on_other_layers(): Rule
    {
        return PHPat::rule()
            ->classes(Selector::namespace('App\Domain'))
            ->shouldNotDependOn()
            ->classes(
                Selector::namespace('App\Application'),
                Selector::namespace('App\Infrastructure'),
                Selector::classname(SuperForbiddenClass::class),
                Selector::classname('/^SomeVendor\\\.*\\\ForbiddenSubfolder\\\.*/', true)
            )
            ->because('this will break our architecture, implement it another way! see /docs/howto.md');
    }
}
