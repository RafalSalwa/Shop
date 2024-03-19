<?php

declare(strict_types=1);

namespace App\Service\GRPC;

use App\Client\AuthClientInterface;
use App\Client\GRPC\UserApiGRPCClient;
use App\Exception\AuthException;
use App\Model\GRPC\UserResponse;
use App\Protobuf\Message\UserDetails;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;
use function dd;
use function is_null;

final class AuthApiGRPCService
{
    private const GRPC_USER_KEY = 'grpc_user';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AuthClientInterface $authApiGRPCClient,
        private readonly UserApiGRPCClient $userApiGRPCClient,
    ) {}

    public function signInUser(string $email, string $password): void
    {
        $tokenPair = $this->authApiGRPCClient->signIn($email, $password);
        $user = $this->getUserCredentialsFromLastSignUp();
        if (true === is_null($user)) {
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
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
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
        } catch (AuthException $e) {
            dd($e->getMessage(), $e->getTraceAsString());
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

    /** @return array<string,string>|null */
    public function getResponses(): ?array
    {
        return $this->authApiGRPCClient->getResponses() + $this->userApiGRPCClient->getResponses();
    }

    public function getUserDetails(string $token): ?UserDetails
    {
        return $this->userApiGRPCClient->getUser($token);
    }
}
