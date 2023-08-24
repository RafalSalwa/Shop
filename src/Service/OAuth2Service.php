<?php

namespace App\Service;

use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class OAuth2Service
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security               $security,
        RequestStack           $requestStack
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function getProfile($appClient)
    {
        return $this->entityManager->getRepository(OAuth2ClientProfile::class)->findOneBy(['client' => $appClient]);
    }

    public function createConsent($appClient)
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();

        $userConsents = $user->getOAuth2UserConsents()->filter(
            fn(OAuth2UserConsent $consent) => $consent->getClient() === $appClient
        )->first() ?: null;
        $userScopes = $userConsents?->getScopes() ?? [];

        $requestedScopes = ['profile', 'email', 'cart'];
        $requestedScopes = array_diff($requestedScopes, $userScopes);
        $scopeNames = [
            'profile' => 'Your profile',
            'cart' => 'Your cart',
            'email' => 'Your email address',
        ];

        $consents = $userConsents ?? new OAuth2UserConsent();
        $consents->setScopes(array_merge($requestedScopes, $userScopes));
        $consents->setClient($appClient);
        $consents->setCreated(new DateTimeImmutable());
        $consents->setExpires(new DateTimeImmutable('+30 days'));
        $consents->setIpAddress($request->getClientIp());
        return $consents;
    }

    public function getClient()
    {
        $clientId = "testclient";
        return $this->entityManager->getRepository(Client::class)->findOneBy(['identifier' => $clientId]);
    }

    public function save(OAuth2UserConsent $consents)
    {
        $this->entityManager->persist($consents);
        $this->entityManager->flush();
    }
}
