<?php

declare(strict_types=1);

namespace App\Client;

use App\Entity\Contracts\ShopUserInterface;
use App\Exception\AuthApiErrorFactory;
use App\Exception\AuthApiRuntimeException;
use App\Exception\AuthenticationExceptionInterface;
use App\Model\TokenPair;
use App\Model\User;
use App\ValueObject\Token;
use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
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
use const JSON_THROW_ON_ERROR;

final readonly class AuthApiClient implements AuthClientInterface
{
    public function __construct(private HttpClientInterface $authApi, private LoggerInterface $logger)
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

            return TokenPair::fromJson($response->getContent());
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface | JsonException $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function signUp(string $email, string $password): bool
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

            return Response::HTTP_OK === $response->getStatusCode();
        } catch (TransportExceptionInterface | JsonException $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage());
        }
    }

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
            $arrResponse = json_decode($response->getContent(), true, JSON_THROW_ON_ERROR);
            if (true === array_key_exists('user', $arrResponse)) {
                return $arrResponse['user']['verification_token'];
            }

            throw new AuthApiRuntimeException('User not found or Api got problems');
        } catch (Throwable $throwable) {
            dd($throwable->getMessage(), $throwable->getCode(), $throwable->getTraceAsString(), $response ?? null);
        }

        return null;
    }

    public function confirmAccount(string $verificationCode): void
    {
        try {
            $this->authApi->request('GET', '/auth/verify/' . $verificationCode)->getContent();
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function getByVerificationCode(string $verificationCode): ShopUserInterface
    {
        try {
            $response = $this->authApi->request('GET', '/auth/code/' . $verificationCode);
            $arrResponse = json_decode($response->getContent(throw: true), true, JSON_THROW_ON_ERROR);

            return new User(id: $arrResponse['user']['id'], email: $arrResponse['user']['email']);
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface | JsonException $exception) {
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
        } catch (ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());

            throw AuthApiErrorFactory::create($exception);
        } catch (TransportExceptionInterface | JsonException $exception) {
            $this->logger->error($exception->getMessage());

            throw new AuthApiRuntimeException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
