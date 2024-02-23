<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Dotenv\Dotenv;

// replace with path to your own project bootstrap file
require_once __DIR__ . '/../vendor/autoload.php';

// replace with mechanism to retrieve EntityManager in your app
function GetEntityManager()
{
    (new Dotenv())->bootEnv(__DIR__ . '/../.env');

    $kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
    $kernel->boot();

    return $kernel->getContainer()
        ->get('doctrine')
        ->getManager()
            ;
}

$entityManager = GetEntityManager();

$commands = [
    // If you want to add your own custom console commands,
    // you can do so here.
];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands,
);
