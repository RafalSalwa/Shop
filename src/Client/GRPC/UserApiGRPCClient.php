<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Exception\AuthException;
use App\Protobuf\Message\GetUserRequest;
use App\Protobuf\Message\UserDetails;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\UserServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use Grpc\ChannelCredentials;
use stdClass;

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

    /** @throws AuthException */
    public function confirmAccount(string $verificationCode): void
    {
        $verifyUserRequest = new VerifyUserRequest();
        $verifyUserRequest->setCode($verificationCode);

        $arrResponse = $this->userServiceClient->VerifyUser($verifyUserRequest)
            ->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $status = $arrResponse[1];
        assert($status instanceof stdClass);
        $statusResponse = new StatusResponse($status);
        if (false === $statusResponse->isOk()) {
            throw new AuthException('missing verification code');
        }
    }

    /** @return array<string, array<string, array<array-key, mixed>>> */
    public function getResponses(): array
    {
        if ([] === $this->responses) {
            return [];
        }

        return $this->responses;
    }

    public function getUser(string $token): ?UserDetails
    {
        $getUserRequest = new GetUserRequest();
        $getUserRequest->setToken($token);

        $arrResponse = $this->userServiceClient->GetUserByToken($getUserRequest)
            ->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        $status = $arrResponse[1];
        assert($status instanceof stdClass);

        $statusResponse = new StatusResponse($status);
        if (false === $statusResponse->isOk()) {
            return null;
        }

        $userResponse = $arrResponse[0];
        assert($userResponse instanceof UserDetails);

        return $userResponse;
    }
}
