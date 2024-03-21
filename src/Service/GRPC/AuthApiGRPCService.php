<?php

declare(strict_types=1);

namespace App\Service\GRPC;

use App\Client\AuthClientInterface;
use App\Client\GRPC\UserApiGRPCClient;
use App\Exception\AuthenticationExceptionInterface;
use App\Exception\AuthException;
use App\Model\GRPC\UserResponse;
use App\Protobuf\Message\UserDetails;
use Grpc\UnaryCall;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use function array_merge;

final class AuthApiGRPCService
{
    private const GRPC_USER_KEY = 'grpc_user';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AuthClientInterface $authApiGRPCClient,
        private readonly UserApiGRPCClient $userApiGRPCClient,
        private readonly LoggerInterface $logger,
    ) {}

    public function signInUser(string $email, string $password): void
    {
        $tokenPair = $this->authApiGRPCClient->signIn($email, $password);
        $user = $this->getUserCredentialsFromLastSignUp();
        if (null === $user) {
            return;
        }

        $user = $user->withTokenPair($tokenPair);
        $this->setUserCredentialsFromLastSignUp($user);
    }

    public function signUpUser(string $email, string $password): void
    {
        try {
            $this->authApiGRPCClient->signUp($email, $password);

            $key = $this->authApiGRPCClient->getVerificationCode($email);
            $userResponse = new UserResponse(
                email: $email,
                password: $password,
                confirmationCode: $key,
                isVerified: false,
            );

            $this->setUserCredentialsFromLastSignUp($userResponse);
        } catch (AuthenticationExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function confirmAccount(string $code): void
    {
        try {
            $this->userApiGRPCClient->confirmAccount($code);

            $userResponse = $this->getUserCredentialsFromLastSignUp();
            if (null !== $userResponse) {
                $this->setUserCredentialsFromLastSignUp($userResponse->withIsVerified(true));
            }
        } catch (AuthException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function getUserCredentialsFromLastSignUp(): ?UserResponse
    {
        return $this->requestStack->getSession()->get(self::GRPC_USER_KEY);
    }

    public function setUserCredentialsFromLastSignUp(UserResponse $arrUser): void
    {
        $this->requestStack->getSession()->set(self::GRPC_USER_KEY, $arrUser);
    }

    /** @return array<string,UnaryCall> */
    public function getResponses(): array
    {
        return array_merge($this->authApiGRPCClient->getResponses(), $this->userApiGRPCClient->getResponses());
    }

    public function getUserDetails(string $token): ?UserDetails
    {
        return $this->userApiGRPCClient->getUser($token);
    }
}
