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
use Psr\Log\LoggerInterface;

use function array_key_exists;
use function assert;

final class AuthApiGRPCClient implements AuthClientInterface
{
    private readonly AuthServiceClient $authServiceClient;

    /** @var array<string, array<array-key, mixed>> */
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

    /** @throws AuthenticationExceptionInterface */
    public function signIn(string $email, string $password): TokenPair
    {
        $signInUserInput = new SignInUserInput();
        $signInUserInput->setEmail($email);
        $signInUserInput->setPassword($password);

        $arrResponse = $this->authServiceClient->SignInUser($signInUserInput)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        if (true === array_key_exists(1, $arrResponse)) {
            $status = $arrResponse[1];
            $statusResponse = new StatusResponse($status);
            if (false === $statusResponse->isOk()) {
                throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
            }
        }

        if (true === array_key_exists(0, $arrResponse)) {
            $signInUserResponse = $arrResponse[0];
            assert($signInUserResponse instanceof SignInUserResponse);

            return new TokenPair(
                new Token($signInUserResponse->getAccessToken()),
                new Token($signInUserResponse->getRefreshToken()),
            );
        }

        throw new AuthException('Unknown Authentication exception');
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

        if (true === array_key_exists(1, $arrResponse)) {
            $status = $arrResponse[1];
            $statusResponse = new StatusResponse($status);
            if (false === $statusResponse->isOk()) {
                throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
            }
        }

        if (true !== array_key_exists(0, $arrResponse)) {
            return;
        }

        $userResponse = $arrResponse[0];
        assert($userResponse instanceof SignUpUserResponse);
    }

    /** @throws AuthenticationExceptionInterface */
    public function getVerificationCode(string $email): string
    {
        $verificationCodeRequest = new VerificationCodeRequest();
        $verificationCodeRequest->setEmail($email);

        $arrResponse = $this->authServiceClient->getVerificationKey($verificationCodeRequest)->wait();
        $this->responses[__FUNCTION__] = $arrResponse;

        if (true === array_key_exists(1, $arrResponse)) {
            $status = $arrResponse[1];
            $statusResponse = new StatusResponse($status);
            if (false === $statusResponse->isOk()) {
                throw AuthApiGRPCExceptionFactory::create($statusResponse->getCode());
            }
        }

        if (true === array_key_exists(0, $arrResponse)) {
            $verificationCodeResponse = $arrResponse[0];
            assert($verificationCodeResponse instanceof VerificationCodeResponse);

            return $verificationCodeResponse->getCode();
        }

        throw new AuthException('Unknown Authentication exception');
    }

    public function confirmAccount(string $verificationCode): void
    {
        $this->logger->critical('Confirm Account should not be called in GRPC flow', ['code' => $verificationCode]);
    }

    /** @return array<string, array<string, array<array-key, mixed>>> */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
