<?php

declare(strict_types=1);

namespace App\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

use function json_decode;

final class ApiResponseExtractor
{
    public static function getErrorMessage(ResponseInterface $response): string
    {
        $content = json_decode($response->getContent(throw: false), true);

        return $content['message'] ?? $content['reason'] ?? '';
    }
}
