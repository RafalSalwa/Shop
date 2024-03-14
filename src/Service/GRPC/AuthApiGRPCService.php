<?php

declare(strict_types=1);

namespace App\Service\GRPC;

use App\Model\GRPC\VerificationCodeRequest;
use App\Protobuf\Generated\AuthServiceClient;
use App\Protobuf\Generated\SignInUserInput;
use App\Protobuf\Generated\SignUpUserInput;
use App\Protobuf\Generated\SignUpUserResponse;
use App\Protobuf\Generated\UserServiceClient;
use App\Protobuf\Generated\VerificationResponse;
use App\Protobuf\Generated\VerifyUserRequest;
use Grpc\ChannelCredentials;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AuthApiGRPCService
{
    private const GRPC_USER_KEY = 'grpc_user';

    private ?AuthServiceClient $authServiceClient = null;

    private ?UserServiceClient $userServiceClient = null;

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly string $authServiceDsn,
        private readonly string $userServiceDsn,
    ) {}

    public function signInUser(string $email, string $password): array
    {
        $signInUserInput = new SignInUserInput();
        $signInUserInput->setUsername($email);
        $signInUserInput->setPassword($password);

        return $this->getAuthClient()
            ->SignInUser($signInUserInput)
            ->wait()
        ;
    }

    public function getAuthClient(): AuthServiceClient
    {
        if (! $this->authServiceClient instanceof AuthServiceClient) {
            $this->authServiceClient = new AuthServiceClient(
                $this->authServiceDsn,
                [
                    'credentials' => ChannelCredentials::createInsecure(),
                ],
            );
        }

        return $this->authServiceClient;
    }

    public function signUpUser(string $email, string $password): array
    {
        $signUpUserInput = new SignUpUserInput();
        $signUpUserInput->setEmail($email);
        $signUpUserInput->setPassword($password);
        $signUpUserInput->setPasswordConfirm($password);

        $response = $this->getAuthClient()->SignUpUser($signUpUserInput)
            ->wait();
        $user = $response[0];
        if ($user instanceof SignUpUserResponse && $user->getVerificationToken()) {
            $arrUser = [
                'username' => $email,
                'password' => $password,
                'vCode' => $user->getVerificationToken(),
                'verified' => false,
            ];
            $this->setUserCredentialsFromLastSignUp($arrUser);
        }
        return $response;
    }

    public function setUserCredentialsFromLastSignUp(array $arrUser): void
    {
        $this->getSession()->set(self::GRPC_USER_KEY, $arrUser);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function verifyUserByCode(string $code): array
    {
        $verifyUserRequest = new VerifyUserRequest();
        $verifyUserRequest->setCode($code);

        $response = $this->getUserClient()->VerifyUser($verifyUserRequest)
            ->wait();

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

    public function getUserCredentialsFromLastSignUp(): ?array
    {
        return $this->getSession()->get(self::GRPC_USER_KEY);
    }

    public function assignVerificationCodeFormLastSignUp(
        VerificationCodeRequest $verificationCodeRequest,
    ): VerificationCodeRequest {
        if (true === $this->getSession()->has(self::GRPC_USER_KEY)) {
            $verificationCodeRequest->setVerificationCode($this->getSession()->get(self::GRPC_USER_KEY));
        }

        return $verificationCodeRequest;
    }
}
