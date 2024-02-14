<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\User;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private readonly HttpClientInterface $authApi,
        private readonly HttpClientInterface $usersApi,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

    public function authenticate(Request $request): Passport
    {
        try {
            $response = $this->authApi->request('POST', '/auth/signin', [
                'body' => json_encode(
                    [
                        'email' => $request->get('email'),
                        'password' => $request->get('password'),
                    ]
                ),
            ]);
            $arrResponse = json_decode($response->getContent(), true);
            $userToken = $arrResponse['user']['token'];
            $userRefreshToken = $arrResponse['user']['refresh_token'];

            return new SelfValidatingPassport(
                new UserBadge($userToken, function () use ($userToken, $userRefreshToken, $request) {
                    $response = $this->usersApi->request('GET', '/user', [
                        'auth_bearer' => $userToken,
                    ]);
                    $user = new User();
                    $user->setFromAuthApi($response);
                    $user->setToken($userToken)->setRefreshToken($userRefreshToken);
                    $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $request->get('email'));

                    return $user;
                }, ['asd' => 'raf']),
                [new RememberMeBadge()]
            );
        } catch (ClientException $ce) {
            dd($ce->getMessage(), $ce->getTraceAsString());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        //        dd(__CLASS__, __FUNCTION__, func_get_args());
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }
}
