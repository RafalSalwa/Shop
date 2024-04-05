<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Entity\Contracts\ShopUserInterface;
use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Security\Contracts\ShopUserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

final class AuthApiTokenAuthenticator extends AbstractAuthenticator implements ShopUserAuthenticatorInterface
{
    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function supports(Request $request): bool
    {
        return Request::METHOD_GET === $request->getMethod()
            && 'register_thank_you' === $request->attributes->get('_route')
            && $request->request->has('verificationCode');
    }

    /** @throws AuthenticationExceptionInterface */
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $verificationCode = $request->request->getAlnum('verificationCode');
        $user = $this->authApiClient->getByVerificationCode($verificationCode);
        $tokenPair = $this->authApiClient->signInByCode($user->getEmail(), $verificationCode);

        $user->setToken($tokenPair->getToken());
        $user->setRefreshToken($tokenPair->getRefreshToken());

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getUserIdentifier()),
            badges: [new RememberMeBadge()],
        );
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        if (true === $request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->getLoginUrl($request));
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    private function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('login_index', $request->query->all());
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
