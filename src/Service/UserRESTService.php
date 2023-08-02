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
        $response = $httpClient->request('GET', 'http://app:8088/user/' . $userid, [
//            'auth_basic' => ['interview', 'interview'],
            'auth_bearer' => 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE2ODU0NzUzNDYsImlhdCI6MTY4NTQ3NDQ0NiwibmJmIjoxNjg1NDc0NDQ2LCJzdWIiOnsiSUQiOjEsIlVzZXJuYW1lIjoiIn19.szTIMEqiKD11uanKvlSYRiAOQ0s566UA-UOE78kmFVQop0Z0d3H3VqdDY0BfKTEqLvN8NIu9iMRlOF2oO_UF_g'
        ]);
        dd(__CLASS__, $response->getContent());
        return $response->getContent();
    }
}