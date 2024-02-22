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
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface     $logger,
    ) {}

    public function signUp(string $email, string $password): bool
    {
        try {
            $response = $this->httpClient->request(
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
            $arrResponse = json_decode((string) $response->getContent(), true);
            if (array_key_exists('status', $arrResponse)) {
                return 'created' === $arrResponse['status'];
            }
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface|JsonException) {
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return false;
    }

    /** @throws \App\Client\AuthenticationExceptionInterface */
    public function signIn(string $email, string $password): ApiTokenPair
    {
        try {
            $response = $this->httpClient->request(
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
        } catch ( TransportExceptionInterface|JsonException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function getVerificationCode(string $email, string $password): ?string
    {
        try {
            $response = $this->httpClient->request(
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
            $arrResponse = json_decode((string) $response->getContent(), true);
            if (array_key_exists('user', $arrResponse)) {
                return $arrResponse['user']['verification_token'];
            }
        } catch (Throwable $throwable) {
            dd($throwable->getMessage(), $throwable->getCode(), $throwable->getTraceAsString(), $response);
        }

        return null;
    }

    public function activateAccount(string $verificationCode): void
    {
        try {
            $this->httpClient->request('GET', '/auth/verify/' . $verificationCode)->getStatusCode();
        } catch (TransportExceptionInterface) {
        }
    }

    public function getByVerificationCode(string $verificationCode): User
    {
        $response = $this->httpClient->request('GET', '/auth/code/' . $verificationCode);
        $user = new User();
        $user->setFromAuthApi($response);

        return $user;
    }
}
