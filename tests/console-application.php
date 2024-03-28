<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;

require __DIR__ . '/../vendor/autoload.php';
require_once 'functions.php';

return new Application(getAppKernel());
