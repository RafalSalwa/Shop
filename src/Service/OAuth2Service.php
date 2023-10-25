<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class OAuth2Service
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly RequestStack $requestStack
    ) {
    }

    public function getProfile($appClient): OAuth2ClientProfile|null
    {
        return $this->entityManager->getRepository(OAuth2ClientProfile::class)->findOneBy([
            'client' => $appClient,
        ]);
    }

    public function createConsent(Client|null $appClient)
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();

        $userConsents = $user->getOAuth2UserConsents()
            ->filter(fn (OAuth2UserConsent $consent) => $consent->getClient() === $appClient)
            ->first() ?: null;
        $userScopes = $userConsents?->getScopes() ?? [];

        $requestedScopes = ['profile', 'email', 'cart'];
        $requestedScopes = array_diff($requestedScopes, $userScopes);

        $consents = $userConsents ?? new OAuth2UserConsent();
        $consents->setScopes(array_merge($requestedScopes, $userScopes));
        $consents->setClient($appClient);
        $consents->setCreated(new \DateTimeImmutable());
        $consents->setExpires(new \DateTimeImmutable('+30 days'));
        $consents->setIpAddress($request->getClientIp());

        return $consents;
    }

    public function getClient(): Client|null
    {
        $clientId = 'testclient';

        return $this->entityManager->getRepository(Client::class)->findOneBy([
            'identifier' => $clientId,
        ]);
    }

    public function save(OAuth2UserConsent $consents): void
    {
        $this->entityManager->persist($consents);
        $this->entityManager->flush();
    }
}
