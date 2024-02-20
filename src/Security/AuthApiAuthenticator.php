<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Client\UsersApiClient;
use App\Exception\AuthenticationExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use function dd;

class AuthApiAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private AuthApiClient $authApiClient,
        private UsersApiClient $usersApiClient,
        private UrlGeneratorInterface $urlGenerator,
    ) {}

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }

    public function authenticate(Request $request): Passport
    {
        if (false === $request->request->has('email') || false === $request->request->has('password')) {
            throw new InvalidArgumentException('Missing authentication parameters in request.');
        }
        try {
            $tokenPair = $this->authApiClient->signIn(
                $request->request->get('email'),
                $request->request->get('password'),
            );
        } catch (AuthenticationExceptionInterface $exception) {
            dd($exception->getMessage(), $exception->getTraceAsString());
        }

        return new SelfValidatingPassport(
            new UserBadge(
                $tokenPair->getToken(),
                function () use ($tokenPair, $request) {
                    $user = $this->usersApiClient->getUser($tokenPair);
                    $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $request->get('email'));

                    return $user;
                },
                ['asd' => 'raf'],
            ),
            [new RememberMeBadge()],
        );
    }
///Analyze "Shopping App": sqp_7b4b9ec745d6ec87b0279a8f062e0da9b590211a
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        dd('fail');
        if (true === $request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }
}
