<?php

declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Contracts\OAuth2UserInterface;
use App\Entity\OAuth2UserConsent;
use App\Repository\OAuth2UserConsentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use function array_diff;
use function array_merge;
use function assert;
use function explode;

final readonly class OAuth2Service
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
        private RequestStack $requestStack,
        private OAuth2UserConsentRepository $repository,
    ) {}

    public function createConsent(): void
    {
        $appClient = $this->getClient();
        assert($appClient instanceof Client);

        $user = $this->getUser();
        assert($user instanceof OAuth2UserInterface);

        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new BadRequestException();
        }

        $oAuth2UserConsent = new OAuth2UserConsent($user->getId(), $appClient);
        $userScopes = [];

        $requestedScopes = ['profile', 'email', 'cart'];
        $requestedScopes = array_diff($requestedScopes, $userScopes);

        $oAuth2UserConsent->setScopes(array_merge($requestedScopes, $userScopes));
        $oAuth2UserConsent->setClient($appClient);
        $oAuth2UserConsent->setExpires(new DateTimeImmutable('+30 days'));
        $oAuth2UserConsent->setIpAddress($request->getClientIp());

        $user = $this->getUser();
        $user->addConsent($oAuth2UserConsent);
        $this->save($oAuth2UserConsent);
    }

    public function getClient(): ?Client
    {
        $clientId = 'testclient';

        return $this->entityManager->getRepository(Client::class)->findOneBy(
            ['identifier' => $clientId],
        );
    }

    private function getUser(): OAuth2UserInterface
    {
        $user = $this->security->getUser();
        assert($user instanceof OAuth2UserInterface);

        return $user;
    }

    public function save(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->entityManager->persist($oAuth2UserConsent);
        $this->entityManager->flush();
    }

    public function getUserConsent(int $userId): ?OAuth2UserConsent
    {
        $client = $this->getClient();
        $userScopes = [];

        $userConsents = $this->repository->findOneBy(
            [
                'userId' => $userId,
                'client' => $client,
            ],
            ['id' => 'DESC'],
        );
        if (true === $userConsents instanceof OAuth2UserConsent) {
            $userScopes = $userConsents->getScopes();
        }

        $request = $this->requestStack->getCurrentRequest();
        assert($request instanceof Request);

        $requestedScopes = explode(' ', $request->query->getAlnum('scope'));
        if ([] === array_diff($requestedScopes, $userScopes)) {
            $session = $request->getSession();
            assert($session instanceof SessionInterface);
            $session->set('consent_granted', true);
        }

        return $userConsents;
    }

    /** @return array<string> */
    public function getUserScopes(int $userId): array
    {
        $consent = $this->getUserConsent($userId);
        if (null === $consent) {
            return [];
        }

        return $consent->getScopes();
    }
}
