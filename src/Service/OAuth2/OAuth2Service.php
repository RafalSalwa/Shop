<?php

declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Contracts\OAuth2UserInterface;
use App\Entity\OAuth2ClientProfile;
use App\Entity\OAuth2UserConsent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use function array_diff;
use function array_merge;
use function assert;

final readonly class OAuth2Service
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private RequestStack $requestStack,
    ) {}

    public function getProfile(Client $appClient): OAuth2ClientProfile|null
    {
        return $this->entityManager->getRepository(OAuth2ClientProfile::class)->findOneBy(
            ['client' => $appClient],
        );
    }

    public function createConsent(Client $appClient): OAuth2UserConsent
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();

        assert($user instanceof OAuth2UserInterface);

        $userConsents = $user->getConsents();

        $userConsents = $userConsents->filter(
            static fn (OAuth2UserConsent $oAuth2UserConsent): bool => $oAuth2UserConsent->getClient() === $appClient,
        );
        $userScopes = ($userConsents?->getScopes() ?? []);

        $requestedScopes = ['profile', 'email', 'cart'];
        $requestedScopes = array_diff($requestedScopes, $userScopes);

        $consents = ($userConsents ?? new OAuth2UserConsent());
        $consents->setScopes(array_merge($requestedScopes, $userScopes));
        $consents->setClient($appClient);
        $consents->setCreated(new DateTimeImmutable());
        $consents->setExpires(new DateTimeImmutable('+30 days'));
        $consents->setIpAddress($request->getClientIp());

        return $consents;
    }

    public function getClient(): Client|null
    {
        $clientId = 'testclient';

        return $this->entityManager->getRepository(Client::class)->findOneBy(
            ['identifier' => $clientId],
        );
    }

    public function save(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->entityManager->persist($oAuth2UserConsent);
        $this->entityManager->flush();
    }
}
