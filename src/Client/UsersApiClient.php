<?php

declare(strict_types=1);

namespace App\Client;

use App\Model\ApiTokenPair;
use App\Model\User;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function array_key_exists;

class UsersApiClient
{
    public function __construct(
        private readonly HttpClientInterface $authApi,
        private readonly HttpClientInterface $usersApi,
    ) {}

    public function signUp(string $email, string $password)
    {
        try {
            $response = $this->authApi->request('POST', '/auth/signup', [
                'body' => json_encode([
                    'email' => $email,
                    'password' => $password,
                    'passwordConfirm' => $password,
                ], JSON_THROW_ON_ERROR),
            ]);
            $arrResponse = json_decode($response->getContent(), true);
            if (true === array_key_exists('status', $arrResponse)) {
                return 'created' === $arrResponse['status'];
            }
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        } catch (JsonException $e) {
        } catch (Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return false;
    }

    public function signIn(string $email, string $password): ?ApiTokenPair
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
            $arrResponse = json_decode($response->getContent(), true, 4, JSON_THROW_ON_ERROR);

            $tokenPair = new ApiTokenPair(
                $arrResponse['user']['token'],
                $arrResponse['user']['refresh_token']
            );
            return $tokenPair;
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        } catch (JsonException $e) {
        } catch (Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return null;
    }

    public function getVerificationCode(string $email, string $password): ?string
    {
        try {
            $response = $this->authApi->request('POST', '/auth/code', [
                'body' => json_encode([
                    'email' => $email,
                    'password' => $password,
                ], JSON_THROW_ON_ERROR),
            ]);
            $arrResponse = json_decode($response->getContent(), true);
            if (true === array_key_exists('user', $arrResponse)) {
                return $arrResponse['user']['verification_token'];
            }
        } catch (Exception $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString(), $response);
        }

        return null;
    }

    public function activateAccount(string $verificationCode): void
    {
        try {
            $this->authApi->request('GET', '/auth/verify/' . $verificationCode)->getStatusCode();
        } catch (TransportExceptionInterface $e) {
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
