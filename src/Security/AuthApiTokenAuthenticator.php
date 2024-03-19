<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Entity\Contracts\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use function dd;

final class AuthApiTokenAuthenticator extends AbstractAuthenticator implements ShopUserAuthenticatorInterface
{
    public function __construct(private readonly AuthApiClient $authApiClient)
    {}

    public function supports(Request $request): ?bool
    {
        return Request::METHOD_GET === $request->getMethod() &&
            'register_thank_you' === $request->attributes->get('_route') &&
            $request->request->has('verificationCode');
    }

    public function authenticate(Request $request): Passport
    {
        $verificationCode = $request->get('verificationCode');
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $tokenPair = $this->authApiClient->signInByCode($user->getEmail(), $verificationCode);

        $user->setToken($tokenPair->getToken());
        $user->setRefreshToken($tokenPair->getRefreshToken());

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getUserIdentifier()),
            badges: [new RememberMeBadge()],
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        dd($request, $token, $firewallName);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dd($request, $exception);
    }

    public function authenticateWithAuthCode(string $authCode): ShopUserInterface
    {
        $user = $this->authApiClient->getByVerificationCode($authCode);
        $tokenPair = $this->authApiClient->signInByCode($user->getEmail(), $authCode);

        $user->setToken($tokenPair->getToken());
        $user->setRefreshToken($tokenPair->getRefreshToken());

        return $user;
    }
}
