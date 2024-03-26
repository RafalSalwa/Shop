<?php

declare(strict_types=1);

namespace App\Service\OAuth2;

use App\Entity\Contracts\OAuth2UserInterface;
use App\Entity\OAuth2UserConsent;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RequestStack;

use function array_diff;
use function array_merge;
use function assert;
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

        return $oAuth2UserConsent;
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
        assert(is_subclass_of($user, OAuth2UserInterface::class));

        return $user;
    }

    public function save(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->entityManager->persist($oAuth2UserConsent);
        $this->entityManager->flush();
    }
}
