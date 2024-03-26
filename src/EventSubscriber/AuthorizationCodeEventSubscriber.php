<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use League\Bundle\OAuth2ServerBundle\Event\AuthorizationRequestResolveEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use function assert;

final class AuthorizationCodeEventSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private string $firewallName;

    public function __construct(
        private readonly Security $security,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack,
    ) {
        $request = $this->requestStack->getCurrentRequest();
        assert($request instanceof Request);

        $this->firewallName = $this->security->getFirewallConfig($request)?->getName() ?? 'main';
    }

    /** @return array<string, string> */
    public static function getSubscribedEvents(): array
    {
        return ['league.oauth2_server.event.authorization_request_resolve' => 'onEventAuthorizationRequest'];
    }

    public function onEventAuthorizationRequest(AuthorizationRequestResolveEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        assert($request instanceof Request);
        $session = $request->getSession();

        $user = $this->security->getUser();
        $this->saveTargetPath($session, $this->firewallName, $request->getUri());
        $response = new RedirectResponse(
            $this->urlGenerator->generate('app_login'),
            Response::HTTP_TEMPORARY_REDIRECT,
        );
        if ($user instanceof UserInterface) {
            if (true === $session->has('consent_granted')) {
                $event->resolveAuthorization($session->get('consent_granted'));
                $session->remove('consent_granted');

                return;
            }

            $consentResponse = new RedirectResponse(
                $this->urlGenerator->generate(
                    'oauth_app_consent',
                    $request->query->all(),
                ),
                Response::HTTP_TEMPORARY_REDIRECT,
            );
            $event->setResponse($consentResponse);
        }

        $event->setResponse($response);
    }
}
