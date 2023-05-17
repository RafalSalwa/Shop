<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserRESTService
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getUserDetails(int $userid)
    {
        $httpClient = HttpClient::create(['headers' => [
            'x-api-key' => 'PHP console app',
        ]]);
        $response = $httpClient->request('GET', 'http://172.28.1.2:8081/user/' . $userid,[
            'auth_basic' => ['interview', 'interview'],
        ]);

        return $response->getContent();
    }
}