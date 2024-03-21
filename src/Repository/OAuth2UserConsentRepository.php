<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OAuth2UserConsent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OAuth2UserConsent>
 * @method  OAuth2UserConsent|null find($id, $lockMode = null, $lockVersion = null)
 * @method  OAuth2UserConsent|null findOneBy(array $criteria, array $orderBy = null)
 * @method  array<OAuth2UserConsent>    findAll()
 * @method  array<OAuth2UserConsent>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OAuth2UserConsentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, OAuth2UserConsent::class);
    }

    public function add(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->getEntityManager()->persist($oAuth2UserConsent);
        $this->getEntityManager()->flush();
    }

    public function remove(OAuth2UserConsent $oAuth2UserConsent): void
    {
        $this->getEntityManager()->remove($oAuth2UserConsent);
        $this->getEntityManager()->flush();
    }
}
