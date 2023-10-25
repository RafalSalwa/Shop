<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    FOS\RestBundle\FOSRestBundle::class => [
        'all' => true,
    ],
    JMS\SerializerBundle\JMSSerializerBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MakerBundle\MakerBundle::class => [
        'dev' => true,
    ],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => [
        'all' => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        'all' => true,
    ],
    KnpU\OAuth2ClientBundle\KnpUOAuth2ClientBundle::class => [
        'all' => true,
    ],
    League\Bundle\OAuth2ServerBundle\LeagueOAuth2ServerBundle::class => [
        'all' => true,
    ],
    Nelmio\CorsBundle\NelmioCorsBundle::class => [
        'all' => true,
    ],
    Nelmio\ApiDocBundle\NelmioApiDocBundle::class => [
        'all' => true,
    ],
    DAMA\DoctrineTestBundle\DAMADoctrineTestBundle::class => [
        'test' => true,
    ],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Symfony\Bundle\MonologBundle\MonologBundle::class => [
        'all' => true,
    ],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => [
        'all' => true,
    ],
    SymfonyCasts\Bundle\VerifyEmail\SymfonyCastsVerifyEmailBundle::class => [
        'all' => true,
    ],
    SymfonyCasts\Bundle\ResetPassword\SymfonyCastsResetPasswordBundle::class => [
        'all' => true,
    ],
];
