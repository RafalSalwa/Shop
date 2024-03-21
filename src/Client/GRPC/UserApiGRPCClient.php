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
use Grpc\UnaryCall;
use stdClass;
use function assert;
use function count;

final class UserApiGRPCClient
{
    private UserServiceClient $userServiceClient;

    /** @var array<string, UnaryCall> */
    private array $responses = [];

    public function __construct(private readonly string $userServiceDsn)
    {
        $this->userServiceClient = new UserServiceClient(
            $userServiceDsn,
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

    /** @return array<string, UnaryCall> */
    public function getResponses(): ?array
    {
        if (0 === count($this->responses)) {
            return [];
        }

        return $this->responses;
    }

    public function getUser(string $token): ?UserDetails
    {
        $userRequest = new GetUserRequest();
        $userRequest->setToken($token);

        $arrResponse = $this->userServiceClient->GetUserByToken($userRequest)
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
