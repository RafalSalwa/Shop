<?php

declare(strict_types=1);

namespace App\Client;

use JsonException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class ApiResponseExtractor
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public static function getErrorMessage(ResponseInterface $response): string
    {
        $content = json_decode($response->getContent(throw: false), true, depth: 512, flags: JSON_THROW_ON_ERROR);

        return $content['message'] ?? $content['reason'] ?? '';
    }
}
