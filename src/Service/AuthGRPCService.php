<?php

namespace App\Service;

use App\Protobuf\Generated\AuthServiceClient;
use App\Protobuf\Generated\SignInUserInput;
use App\Protobuf\Generated\SignUpUserInput;
use App\Protobuf\Generated\SignUpUserResponse;
use App\Protobuf\Generated\UserServiceClient;
use App\Protobuf\Generated\VerifyUserRequest;
use Grpc\ChannelCredentials;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthGRPCService
{
    private const GRPC_USER_KEY = "grpc_user";
    private ?AuthServiceClient $authClient = null;
    private ?UserServiceClient $userClient = null;

    public function __construct(private readonly RequestStack $requestStack,)
    {
    }

    public function signInUser(string $email, string $password)
    {
        $signInReq = new SignInUserInput();
        $signInReq->setUsername($email);
        $signInReq->setPassword($password);

        return $this->getAuthClient()->SignInUser($signInReq)->wait();
    }

    public function getAuthClient(): AuthServiceClient
    {
        if ($this->authClient === null) {
            $this->authClient = new AuthServiceClient('auth_service:8022', [
                'credentials' => ChannelCredentials::createInsecure(),
            ]);
        }
        return $this->authClient;
    }

    public function signUpUser(string $email, string $password): array
    {
        $signUpReq = new SignUpUserInput();
        $signUpReq->setEmail($email);
        $signUpReq->setPassword($password);
        $signUpReq->setPasswordConfirm($password);
        $response = $this->getAuthClient()->SignUpUser($signUpReq)->wait();
        $user = $response[0];
        if ($user instanceof SignUpUserResponse) {
            if ($user->getVerificationToken()) {
                $arrUser = [
                    "username" => $user->getUsername(),
                    "password" => $password,
                    "vCode" => $user->getVerificationToken(),
                    "verified" => false
                ];

                $this->setUserCredentialsFromLastSignUp($arrUser);
            }
        }
        return $response;
    }

    public function setUserCredentialsFromLastSignUp(array $arrUser)
    {

        $this->getSession()->set(self::GRPC_USER_KEY, $arrUser);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    public function getVcodeFromLastSignUp(): ?string
    {
        $arrUser = $this->getSession()->get(self::GRPC_USER_KEY);
        return $arrUser["vCode"];
    }

    public function verifyCode(string $code)
    {
        $verifyCodeRequest = new VerifyUserRequest();
        $verifyCodeRequest->setCode($code);

        $response = $this->getUserClient()->VerifyUser($verifyCodeRequest)->wait();
        $returnObject = $response[0];
        if ($returnObject->getSuccess()) {
            $arrUser = $this->getUserCredentialsFromLastSignUp();
            $arrUser['verified'] = true;
            $this->setUserCredentialsFromLastSignUp($arrUser);
        }
        return $response;
    }

    public function getUserClient(): UserServiceClient
    {
        if ($this->userClient === null) {
            $this->userClient = new UserServiceClient('user_service:8032', [
                'credentials' => ChannelCredentials::createInsecure(),
            ]);
        }
        return $this->userClient;
    }

    public function getUserCredentialsFromLastSignUp(): ?array
    {
        return $this->getSession()->get(self::GRPC_USER_KEY);
    }
}