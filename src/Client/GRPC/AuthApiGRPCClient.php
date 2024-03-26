<?php

declare(strict_types=1);

namespace App\Client\GRPC;

use App\Client\Contracts\AuthClientInterface;
use App\Exception\AuthException;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Exception\Factory\AuthApiGRPCExceptionFactory;
use App\Model\TokenPair;
use App\Protobuf\Message\SignInUserInput;
use App\Protobuf\Message\SignInUserResponse;
use App\Protobuf\Message\SignUpUserInput;
use App\Protobuf\Message\SignUpUserResponse;
use App\Protobuf\Message\VerificationCodeRequest;
use App\Protobuf\Message\VerificationCodeResponse;
use App\Protobuf\Service\AuthServiceClient;
use App\ValueObject\GRPC\StatusResponse;
use App\ValueObject\Token;
use Grpc\ChannelCredentials;
use Grpc\UnaryCall;
use Psr\Log\LoggerInterface;
use stdClass;

use function assert;

final class AuthApiGRPCClient implements AuthClientInterface
{
    private readonly AuthServiceClient $authServiceClient;

    /** @var array<string, UnaryCall> */
    private array $responses = [];

    public function __construct(
        private readonly string $authServiceDsn,
        private readonly LoggerInterface $logger,
    ) {
        $this->authServiceClient = new AuthServiceClient(
            $this->authServiceDsn,
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ],
        );
    }

    /** @throws AuthException */
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

    /** @throws AuthenticationExceptionInterface */
    public function signUp(string $email, string $password): void
    {
        $signUpUserInput = new SignUpUserInput();
        $signUpUserInput->setEmail($email);
        $signUpUserInput->setPassword($password);
        $signUpUserInput->setPasswordConfirm($password);

        $arrResponse = $this->authServiceClient->SignUpUser($signUpUserInput)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        $statusResponse = new StatusResponse($arrResponse[1]);
        if (false === $statusResponse->isOk()) {
            throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
        }

        $userResponse = $arrResponse[0];
        assert($userResponse instanceof SignUpUserResponse);
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
        $this->logger->critical('Confirm Account should not be called in GRPC flow', ['code' => $verificationCode]);
    }

    /** @return array<string, UnaryCall> */
    public function getResponses(): array
    {
        if ([] === $this->responses) {
            return [];
        }

        return $this->responses;
    }
}
