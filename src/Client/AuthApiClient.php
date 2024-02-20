<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\AuthenticationExceptionInterface;
use App\Exception\AuthApiErrorFactory;
use App\Model\ApiTokenPair;
use App\Model\User;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;
use function array_key_exists;
use function dd;
use function json_decode;
use function json_encode;
use JsonException;
use const JSON_THROW_ON_ERROR;

class AuthApiClient
{
    public function __construct(
        private readonly HttpClientInterface $authApi,
        private readonly LoggerInterface     $logger,
    ) {}

    public function signUp(string $email, string $password)
    {
        try {
            $response = $this->authApi->request(
                'POST',
                '/auth/signup',
                [
                    'body' => json_encode(
                        [
                            'email' => $email,
                            'password' => $password,
                            'passwordConfirm' => $password,
                        ],
                        \JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $arrResponse = json_decode($response->getContent(), true);
            if (true === array_key_exists('status', $arrResponse)) {
                return 'created' === $arrResponse['status'];
            }
        } catch (ClientExceptionInterface) {
        } catch (RedirectionExceptionInterface) {
        } catch (ServerExceptionInterface) {
        } catch (TransportExceptionInterface) {
        } catch (JsonException) {
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return false;
    }

    /** @throws \App\Client\AuthenticationExceptionInterface */
    public function signIn(string $email, string $password): ApiTokenPair
    {
        try {
            $response = $this->authApi->request(
                'POST',
                '/auth/signin',
                [
                    'body' => json_encode(
                        [
                            'email' => $email,
                            'password' => $password,
                        ],
                    ),
                ],
            );

            return ApiTokenPair::fromJson($response->getContent());
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());
            $apiException = AuthApiErrorFactory::create($exception);

            throw $apiException;
        } catch ( TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());
        } catch (JsonException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function getVerificationCode(string $email, string $password): ?string
    {
        try {
            $response = $this->authApi->request(
                'POST',
                '/auth/code',
                [
                    'body' => json_encode(
                        [
                            'email' => $email,
                            'password' => $password,
                        ],
                        \JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $arrResponse = json_decode($response->getContent(), true);
            if (true === array_key_exists('user', $arrResponse)) {
                return $arrResponse['user']['verification_token'];
            }
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return null;
    }

    public function activateAccount(string $verificationCode): void
    {
        try {
            $this->authApi->request('GET', '/auth/verify/' . $verificationCode)->getStatusCode();
        } catch (TransportExceptionInterface) {
        }
    }

    public function getByVerificationCode(string $verificationCode): User
    {
        $response = $this->authApi->request('GET', '/auth/code/' . $verificationCode);
        $user = new User();
        $user->setFromAuthApi($response);

        return $user;
    }
}
