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

// replace with mechanism to retrieve EntityManager in your app
function getEntityManager(): EntityManagerInterface
{
    (new Dotenv())->bootEnv(__DIR__ . '/../.env');

    assert(array_key_exists('APP_ENV', $_SERVER));
    assert(array_key_exists('APP_DEBUG', $_SERVER));

    $env = $_SERVER['APP_ENV'];
    assert('' !== $env);
    $debug = (bool)$_SERVER['APP_DEBUG'];

    $kernel = new Kernel($env, $debug);
    $kernel->boot();

    return $kernel->getContainer()->get('doctrine')?->getManager();
}

$entityManager = getEntityManager();

$commands = [];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands,
);
