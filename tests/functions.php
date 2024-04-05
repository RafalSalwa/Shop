<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Dotenv\Dotenv;

// phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
if (false === function_exists('getAppEntityManager')) {
    function getAppEntityManager(): EntityManagerInterface
    {
        (new Dotenv())->bootEnv(__DIR__ . '/../.env');

        assert(array_key_exists('APP_ENV', $_SERVER));
        assert(array_key_exists('APP_DEBUG', $_SERVER));

        $env = $_SERVER['APP_ENV'];
        assert('' !== $env);
        $debug = (bool)$_SERVER['APP_DEBUG'];

        $kernel = new Kernel($env, $debug);
        $kernel->boot();

        $doctrineRegistry = $kernel->getContainer()->get('doctrine');
        $manager = $doctrineRegistry->getManager();
        assert($manager instanceof EntityManagerInterface);

        return $manager;
    }
}

if (false === function_exists('getAppKernel')) {
    function getAppKernel(): Kernel
    {
        (new Dotenv())->bootEnv(__DIR__ . '/../.env');

        assert(array_key_exists('APP_ENV', $_SERVER));
        assert(array_key_exists('APP_DEBUG', $_SERVER));

        $env = $_SERVER['APP_ENV'];
        assert('' !== $env);
        $debug = (bool)$_SERVER['APP_DEBUG'];

        return new Kernel($env, $debug);
    }
}
