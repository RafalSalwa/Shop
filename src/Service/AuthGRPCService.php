<?php

namespace App\Service;

use App\Protobuf\Generated\AuthServiceClient;
use App\Protobuf\Generated\SignInUserInput;
use App\Protobuf\Generated\SignInUserResponse;
use Grpc;
class AuthGRPCService
{
    private ?AuthServiceClient $client = null;

    public function getClient(): AuthServiceClient
    {
        if ($this->client === null) {
            $this->client = new AuthServiceClient('app:8089', [
                'credentials' => Grpc\ChannelCredentials::createInsecure(),
            ]);
        }
        return $this->client;
    }

    public function getAuthTokens(string $username, string $password): ?SignInUserResponse
    {
        $signInReq = new SignInUserInput();
        $signInReq->setUsername($username);
        $signInReq->setPassword($password);

        [$response, $status] = $this->getClient()->SignInUser($signInReq)->wait();
        return $response;
    }
}