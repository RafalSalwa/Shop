<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;

use function Termwind\render;

abstract class AbstractSymfonyCommand extends Command
{
    public function render(string $html): void
    {
        render($html);
    }
}
