<?php

namespace App\EventSubscriber;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthorizationCodeSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $firewallName;

    public function __construct(
        private readonly Security              $security,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack          $requestStack,
        FirewallMapInterface                   $firewallMap
    ) {
        $this->firewallName = $firewallMap->getFirewallConfig($requestStack->getCurrentRequest())->getName();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'league.oauth2_server.event.authorization_request_resolve' => 'onLeagueOauth2ServerEventAuthorizationRequestResolve',
        ];
    }

    public function onLeagueOauth2ServerEventAuthorizationRequestResolve(AuthorizationRequestResolveEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $this->security->getUser();
        $this->saveTargetPath($request->getSession(), $this->firewallName, $request->getUri());
        $response = new RedirectResponse($this->urlGenerator->generate('app_login'), Response::HTTP_TEMPORARY_REDIRECT);
        if ($user instanceof UserInterface) {
            if (null !== $request->getSession()->get('consent_granted')) {
                $event->resolveAuthorization($request->getSession()->get('consent_granted'));
                $request->getSession()->remove('consent_granted');
                return;
            }
            $response = new RedirectResponse($this->urlGenerator->generate('app_consent', $request->query->all()), Response::HTTP_TEMPORARY_REDIRECT);
        }
        $event->setResponse($response);
    }
}
