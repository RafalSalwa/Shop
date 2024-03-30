#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Dotenv\Dotenv;

// replace with path to your own project bootstrap file
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../tests/functions.php';
$commands = [];

ConsoleRunner::run(
    new SingleManagerProvider(getAppEntityManager()),
    $commands,
);
