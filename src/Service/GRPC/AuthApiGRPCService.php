<?php

declare(strict_types=1);

namespace App\Service\GRPC;

use App\Client\AuthClientInterface;
use App\Client\GRPC\UserApiGRPCClient;
use App\Exception\AuthException;
use App\Model\GRPC\UserResponse;
use App\Model\TokenPair;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;
use function dd;

final class AuthApiGRPCService
{
    private const GRPC_USER_KEY = 'grpc_user';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AuthClientInterface $authApiGRPCClient,
        private readonly UserApiGRPCClient $userApiGRPCClient,
    ) {}

    public function signInUser(string $email, string $password): TokenPair
    {
        $tokenPair = $this->authApiGRPCClient->signIn($email, $password);
        $user = $this->getUserCredentialsFromLastSignUp();
        if (null !== $user) {
            $this->setUserCredentialsFromLastSignUp($user->withTokenPair($tokenPair));
        }

        return $tokenPair;
    }

    public function signUpUser(string $email, string $password): bool
    {
        try {
            $this->authApiGRPCClient->signUp($email, $password);

            $key = $this->authApiGRPCClient->getVerificationCode($email);
            $userResponse = new UserResponse(email: $email, password: $password, vCode: $key, isVerified: false);

            $this->setUserCredentialsFromLastSignUp($userResponse);

            return true;
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function confirmAccount(string $code): bool
    {
        try {
            $this->userApiGRPCClient->confirmAccount($code);

            $userResponse = $this->getUserCredentialsFromLastSignUp();
            if (null !== $userResponse) {
                $this->setUserCredentialsFromLastSignUp($userResponse->withIsVerified(true));
            }

            return true;
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

    /**
     * @throws AuthException
     */
    public function getUserDetails(string $token):mixed
    {
        return $this->userApiGRPCClient->getUser($token);
    }
}
