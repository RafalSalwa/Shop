<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Client\AuthClientInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\AuthException;
use App\Model\TokenPair;
use App\Protobuf\Message\SignInUserInput;
use App\Protobuf\Message\SignUpUserInput;
use App\Protobuf\Message\VerificationCodeRequest;
use App\Protobuf\Message\VerificationCodeResponse;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\UserServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use App\ValueObject\Token;
use Grpc\ChannelCredentials;
use stdClass;
use function assert;
use function dd;

final class UserApiGRPCClient implements AuthClientInterface
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

    public function signIn(string $email, string $password): TokenPair
    {
        $signInUserInput = new SignInUserInput();
        $signInUserInput->setUsername($email);
        $signInUserInput->setPassword($password);
        $arrResponse = $this->authServiceClient->SignInUser($signInUserInput)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;
        $arrStatus = $arrResponse[1];
        $arrMessage = $arrResponse[0];

        dd($arrMessage, $arrStatus);
        $token = new Token('asd');
        $refreshToken = new Token('asd');

        return new TokenPair($token, $refreshToken);
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

        $response = $this->userServiceClient->VerifyUser($verifyUserRequest)
            ->wait();

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
            return null;
        }
        return $this->responses;

    }
}
