<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

use function dirname;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
(new Filesystem())->remove(__DIR__ . '/../var/cache/test');
