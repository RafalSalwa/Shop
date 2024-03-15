<?php

declare(strict_types=1);

namespace App\Service\GRPC;

use App\Client\AuthClientInterface;
use App\Model\GRPC\UserResponse;
use App\Model\GRPC\VerificationCodeRequest;
use App\Protobuf\Message\VerificationResponse;
use App\Protobuf\Message\VerifyUserRequest;
use App\Protobuf\Service\UserServiceClient;
use Exception;
use Grpc\ChannelCredentials;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use function assert;

final class AuthApiGRPCService
{
    private const GRPC_USER_KEY = 'grpc_user';

    private ?UserServiceClient $userServiceClient = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AuthClientInterface $authApiGRPCClient,

    ) {}

    public function signInUser(string $email, string $password): array
    {
        return $this->authApiGRPCClient->signIn($email, $password);
    }

    public function signUpUser(string $email, string $password): bool
    {
        try{
        $this->authApiGRPCClient->signUp($email, $password);

        $key = $this->authApiGRPCClient->getVerificationCode($email);
        $userResponse = new UserResponse(email: $email, password: $password, vCode: $key, isVerified: false);

        $this->setUserCredentialsFromLastSignUp($userResponse);

        return true;
        } catch(Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function getUserCredentialsFromLastSignUp(): ?UserResponse
    {
        return $this->getSession()->get(self::GRPC_USER_KEY);
    }

    public function setUserCredentialsFromLastSignUp(UserResponse $arrUser): void
    {
        $this->getSession()->set(self::GRPC_USER_KEY, $arrUser);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function verifyUserByCode(string $code): array
    {
        $this->authApiGRPCClient->confirmAccount($code);

        $returnObject = $response[0];
        assert($returnObject instanceof VerificationResponse);
        if (true === $returnObject->getSuccess()) {
            $arrUser = $this->getUserCredentialsFromLastSignUp();
            $arrUser['verified'] = true;
            $this->setUserCredentialsFromLastSignUp($arrUser);
        }

        return $response;
    }

    public function getUserClient(): UserServiceClient
    {
        if (! $this->userServiceClient instanceof UserServiceClient) {
            $this->userServiceClient = new UserServiceClient(
                $this->userServiceDsn,
                [
                    'credentials' => ChannelCredentials::createInsecure(),
                ],
            );
        }

        return $this->userServiceClient;
    }

    public function assignVerificationCodeFormLastSignUp(
        VerificationCodeRequest $verificationCodeRequest,
    ): VerificationCodeRequest {
        if (true === $this->getSession()->has(self::GRPC_USER_KEY)) {
            $verificationCodeRequest->setVerificationCode($this->getSession()->get(self::GRPC_USER_KEY));
        }

        return $verificationCodeRequest;
    }

    public function getResponses(): ?array
    {
        return $this->authApiGRPCClient->getResponses();
    }

    public function getLastResponseStatus()
    {
        return $this->authApiGRPCClient->getLastResponse(1);
    }

    public function getLastResponseObject()
    {
       return $this->authApiGRPCClient->getLastResponse(0);
    }
}
