<?php

declare(strict_types=1);

namespace App\Client;

use App\Client\Contracts\AuthClientInterface;
use App\Client\Contracts\AuthCodeClientInterface;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\AuthApiErrorFactory;
use App\Exception\AuthApiRuntimeException;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Model\TokenPair;
use App\Model\User;
use App\ValueObject\Token;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function array_key_exists;
use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

final class AuthApiClient implements AuthClientInterface, AuthCodeClientInterface
{
    /** @var array<string, array<array-key, mixed>> */
    private array $responses = [];

    public function __construct(private readonly HttpClientInterface $authApi, private readonly LoggerInterface $logger)
    {}

    /** @throws AuthenticationExceptionInterface */
    public function signIn(string $email, string $password): TokenPair
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
                        JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $content = $response->getContent();
            $this->responses[__FUNCTION__] = $response->toArray(false);

            return TokenPair::fromJson($content);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /** @throws AuthApiRuntimeException */
    public function signUp(string $email, string $password): void
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
                        JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $this->responses[__FUNCTION__] = $response->toArray(false);
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException(message: $exception->getMessage(), previous: $exception);
        }
    }

    /** @throws AuthApiRuntimeException */
    public function getVerificationCode(string $email): ?string
    {
        try {
            $response = $this->authApi->request(
                'POST',
                '/auth/code',
                [
                    'body' => json_encode(
                        ['email' => $email],
                        JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $content = $response->getContent();
            $this->responses[__FUNCTION__] = $response->toArray(false);
            $arrResponse = json_decode($content, true, JSON_THROW_ON_ERROR);
            if (true === array_key_exists('user', $arrResponse)) {
                return $arrResponse['user']['verification_token'];
            }

            throw new AuthApiRuntimeException('User not found or Api got problems');
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException(message: $exception->getMessage(), previous: $exception);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $httpException) {
            $this->logger->error($httpException->getMessage());

            throw new AuthApiRuntimeException(message: $httpException->getMessage(), previous: $httpException);
        }
    }

    /** @throws AuthenticationExceptionInterface */
    public function confirmAccount(string $verificationCode): void
    {
        try {
            $response = $this->authApi->request('GET', '/auth/verify/' . $verificationCode);
            $response->getContent();
            $this->responses[__FUNCTION__] = $response->toArray(false);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /** @throws AuthenticationExceptionInterface */
    public function getByVerificationCode(string $verificationCode): ShopUserInterface
    {
        try {
            $response = $this->authApi->request('GET', '/auth/code/' . $verificationCode);
            $arrResponse = json_decode($response->getContent(throw: true), true, JSON_THROW_ON_ERROR);

            return new User(id: $arrResponse['user']['id'], email: $arrResponse['user']['email']);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function signInByCode(string $email, string $verificationCode): TokenPair
    {
        try {
            $response = $this->authApi->request(
                'POST',
                '/auth/signin/' . $verificationCode,
                [
                    'body' => json_encode(
                        ['email' => $email],
                        JSON_THROW_ON_ERROR,
                    ),
                ],
            );
            $arrResponse = json_decode($response->getContent(), true, JSON_THROW_ON_ERROR);
            $token = new Token($arrResponse['user']['token']);
            $refreshToken = new Token($arrResponse['user']['refresh_token']);

            return new TokenPair($token, $refreshToken);
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (JsonException | TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /** @return array<string, array<string, array<array-key, mixed>>> */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
