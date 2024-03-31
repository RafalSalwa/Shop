<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Exception\AuthException;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Exception\Factory\AuthApiGRPCExceptionFactory;
use App\Protobuf\Message\GetUserRequest;
use App\Protobuf\Message\UserDetails;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\UserServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use Grpc\ChannelCredentials;

use function array_key_exists;
use function assert;

final class UserApiGRPCClient
{
    private readonly UserServiceClient $userServiceClient;

    /** @var array<string, array<string, array<array-key, mixed>>> */
    private array $responses = [];

    public function __construct(private readonly string $userServiceDsn)
    {
        $this->userServiceClient = new UserServiceClient(
            $this->userServiceDsn,
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ],
        );
    }

    /** @throws AuthenticationExceptionInterface */
    public function confirmAccount(string $verificationCode): void
    {
        $verifyUserRequest = new VerifyUserRequest();
        $verifyUserRequest->setCode($verificationCode);

        $arrResponse = $this->userServiceClient->VerifyUser($verifyUserRequest)
            ->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        if (false === array_key_exists(1, $arrResponse)) {
            return;
        }

        $status = $arrResponse[1];
        $statusResponse = new StatusResponse($status);
        if (false === $statusResponse->isOk()) {
            throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
        }
    }

    /** @throws AuthenticationExceptionInterface */
    public function getUser(string $token): UserDetails
    {
        $getUserRequest = new GetUserRequest();
        $getUserRequest->setToken($token);

        $arrResponse = $this->userServiceClient->GetUserByToken($getUserRequest)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        if (true === array_key_exists(1, $arrResponse)) {
            $status = $arrResponse[1];
            $statusResponse = new StatusResponse($status);
            if (false === $statusResponse->isOk()) {
                throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
            }
        }

        if (true === array_key_exists(0, $arrResponse)) {
            $userResponse = $arrResponse[0];
            assert($userResponse instanceof UserDetails);

            return $userResponse;
        }

        throw new AuthException('Unknown Authentication exception');
    }

    /** @return array<string, array<string, array<array-key, mixed>>> */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
