<?php

use App\Command\AbstractSymfonyCommand;
use PHPat\Selector\Selector;
use PHPat\Test\PHPat;

test('services does not depend on entities', function () {
    return PHPat::rule()
        ->classes(Selector::inNamespace('App\Command'))
        ->shouldExtend()
        ->classes(AbstractSymfonyCommand::class)
        ->because('We want to be able to render html output in console outputs');

});
