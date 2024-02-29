<?php

declare(strict_types=1);

namespace App\Security;

use App\Client\AuthApiClient;
use App\Client\UsersApiClient;
use App\Entity\ShopUserInterface;
use App\Exception\AuthenticationExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Event\AuthenticationTokenCreatedEvent;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use function dd;

final class AuthApiAuthenticator extends AbstractLoginFormAuthenticator implements ShopUserAuthenticatorInterface, UserAuthenticatorInterface
{
    public function __construct(
        private readonly AuthApiClient $authApiClient,
        private readonly UsersApiClient $usersApiClient,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly LoggerInterface $logger,
    ) {}

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
        } catch (AuthenticationExceptionInterface $authenticationException) {
            $this->logger->critical($authenticationException->getMessage());
            dd($authenticationException->getMessage(), $authenticationException->getTraceAsString());
        }

        try {
            $user = $this->usersApiClient->loadUserByIdentifier($tokenPair->getToken());
        } catch (AuthenticationExceptionInterface) {
            dd('a');
        }

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getUserIdentifier()),
            badges: [new RememberMeBadge()],
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    ///Analyze "Shopping App": sqp_7b4b9ec745d6ec87b0279a8f062e0da9b590211a

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $authenticationException,
    ): Response {
        dd('fail');
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $authenticationException);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('login_index');
    }

    public function authenticateUser(
        UserInterface $user,
        AuthenticatorInterface $authenticator,
        Request $request,
        array $badges = [],
    ): ?Response {
        // create an authentication token for the User
        $passport = new SelfValidatingPassport(
            new UserBadge($user->getUserIdentifier(), static fn () => $user),
            $badges,
        );
        $token = $authenticator->createToken($passport, $this->firewallName);

        // announce the authentication token
        $token = $this->eventDispatcher->dispatch(
            new AuthenticationTokenCreatedEvent($token, $passport),
        )->getAuthenticatedToken();

        // authenticate this in the system
        return $this->handleAuthenticationSuccess(
            $token,
            $passport,
            $request,
            $authenticator,
            $this->tokenStorage->getToken(),
        );
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
