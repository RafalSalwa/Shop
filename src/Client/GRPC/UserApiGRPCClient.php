<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Exception\AuthException;
use App\Protobuf\Message\GetUserRequest;
use App\Protobuf\Message\UserDetails;
use App\Protobuf\Message\VerificationCodeRequest;
use App\Protobuf\Message\VerificationCodeResponse;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\UserServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use Grpc\ChannelCredentials;
use stdClass;
use function assert;

final class UserApiGRPCClient
{
    private UserServiceClient $userServiceClient;

    private array $responses = [];

    public function __construct(private readonly string $userServiceDsn,)
    {
        $this->userServiceClient = new UserServiceClient(
            $this->userServiceDsn,
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ],
        );
    }

    /** @throws AuthException */
    public function getVerificationCode(string $email): string
    {
        $verificationCodeRequest = new VerificationCodeRequest();
        $verificationCodeRequest->setEmail($email);
        $arrResponse = $this->authServiceClient->getVerificationKey($verificationCodeRequest)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $arrStatus = $arrResponse[1];

        assert($arrStatus instanceof stdClass);
        $statusResponse = new StatusResponse($arrStatus);
        if (false === $statusResponse->isOk()) {
            throw new AuthException('missing verification code');
        }
        $verificationCodeResponse = $arrResponse[0];
        assert($verificationCodeResponse instanceof VerificationCodeResponse);

        return $verificationCodeResponse->getCode();
    }

    /**
     * @throws AuthException
     */
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

    public function getResponses(): ?array
    {
        if (0 === count($this->responses)) {
            return [];
        }
        return $this->responses;

    }

    public function getUser(string $token): array
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
           return [];
        }

        $userResponse = $arrResponse[0];
        assert($userResponse instanceof UserDetails);

        return [
            'id'=>$userResponse->getId(),
            'email'=>$userResponse->getEmail(),
            'verified'=>$userResponse->getVerified(),
            'active'=>$userResponse->getActive(),
            'createdAt'=>$userResponse->getCreatedAt()->toDateTime()->format('d-m-Y H:i:s'),
        ];
    }
}
