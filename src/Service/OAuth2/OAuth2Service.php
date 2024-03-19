<?php

declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Contracts\OAuth2UserInterface;
use App\Entity\OAuth2UserConsent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use function array_diff;
use function array_merge;
use function assert;
use function is_null;
use function is_subclass_of;

final readonly class OAuth2Service
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private RequestStack $requestStack,
    ) {}

    public function createConsent(Client $appClient): OAuth2UserConsent
    {
        $user = $this->getUser();
        $request = $this->requestStack->getCurrentRequest();
        $consent = new OAuth2UserConsent();
        $userScopes = [];

        $userConsents = $user->getConsents();
        if (false === is_null($userConsents)) {
            $userConsents = $userConsents->filter(
                static fn (OAuth2UserConsent $consent): bool => $consent->getClient() === $appClient,
            );
            $consent = $userConsents->first();
            $userScopes = ($consent ?? []);
        }

        $requestedScopes = ['profile', 'email', 'cart'];
        $requestedScopes = array_diff($requestedScopes, $userScopes);

        $consent->setScopes(array_merge($requestedScopes, $userScopes));
        $consent->setClient($appClient);
        $consent->setCreated(new DateTimeImmutable());
        $consent->setExpires(new DateTimeImmutable('+30 days'));
        $consent->setIpAddress($request->getClientIp());

        return $consent;
    }

    public function getClient(): Client|null
    {
        $clientId = 'testclient';

        return $this->entityManager->getRepository(Client::class)->findOneBy(
            ['identifier' => $clientId],
        );
    }

    private function getUser(): OAuth2UserInterface
    {
        $user = $this->security->getUser();
        assert(is_subclass_of($user, OAuth2UserInterface::class));

        return $user;
    }

    public function save(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->entityManager->persist($oAuth2UserConsent);
        $this->entityManager->flush();
    }
}
