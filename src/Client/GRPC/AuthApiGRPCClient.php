<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Client\AuthClientInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\AuthException;
use App\Model\TokenPair;
use App\Protobuf\Message\SignInUserInput;
use App\Protobuf\Message\SignInUserResponse;
use App\Protobuf\Message\SignUpUserInput;
use App\Protobuf\Message\VerificationCodeRequest;
use App\Protobuf\Message\VerificationCodeResponse;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\AuthServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use App\ValueObject\Token;
use Grpc\ChannelCredentials;
use stdClass;
use function assert;

final class AuthApiGRPCClient implements AuthClientInterface
{
    private AuthServiceClient $authServiceClient;

    private array $responses = [];

    public function __construct(private string $authServiceDsn)
    {
        $this->authServiceClient = new AuthServiceClient(
            $this->authServiceDsn,
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ],
        );
    }

    public function signIn(string $email, string $password): TokenPair
    {
        $signInUserInput = new SignInUserInput();
        $signInUserInput->setEmail($email);
        $signInUserInput->setPassword($password);
        $arrResponse = $this->authServiceClient->SignInUser($signInUserInput)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $arrStatus = $arrResponse[1];
        assert($arrStatus instanceof stdClass);
        $statusResponse = new StatusResponse($arrStatus);
        if (false === $statusResponse->isOk()) {
            throw new AuthException('missing verification code');
        }
        $userResponse = $arrResponse[0];
        assert($userResponse instanceof SignInUserResponse);

        return new TokenPair(new Token($userResponse->getAccessToken()), new Token($userResponse->getRefreshToken()));
    }

    public function signUp(string $email, string $password): bool
    {
        $signUpUserInput = new SignUpUserInput();
        $signUpUserInput->setEmail($email);
        $signUpUserInput->setPassword($password);
        $signUpUserInput->setPasswordConfirm($password);

        $arrResponse = $this->authServiceClient->SignUpUser($signUpUserInput)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $arrStatus = $arrResponse[1];

        assert($arrStatus instanceof stdClass);

        return (new StatusResponse($arrStatus))->isOk();
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

    public function confirmAccount(string $verificationCode): void
    {
        $verifyUserRequest = new VerifyUserRequest();
        $verifyUserRequest->setCode($verificationCode);

        $arrResponse = $this->getUserClient()->VerifyUser($verifyUserRequest)
            ->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $arrStatus = $arrResponse[1];

        assert($arrStatus instanceof stdClass);
        $statusResponse = new StatusResponse($arrStatus);
        if (false === $statusResponse->isOk()) {
            throw new AuthException('missing verification code');
        }
    }

    public function getByVerificationCode(string $verificationCode): ShopUserInterface
    {
        // TODO: Implement getByVerificationCode() method.
    }

    public function signInByCode(string $email, string $verificationCode): TokenPair
    {
        // TODO: Implement signInByCode() method.
    }

    public function getResponses(): ?array
    {
        if (0 === count($this->responses)) {
            return [];
        }
        return $this->responses;

    }
}
