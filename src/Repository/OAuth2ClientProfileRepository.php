<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\OAuth2ClientProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OAuth2ClientProfile>
 * @method  OAuth2ClientProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method  OAuth2ClientProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method  array<OAuth2ClientProfile>    findAll()
 * @method  array<OAuth2ClientProfile>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class OAuth2ClientProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, OAuth2ClientProfile::class);
    }

    public function add(OAuth2ClientProfile $oAuth2ClientProfile, bool $flush = false): void
    {
        $this->getEntityManager()->persist($oAuth2ClientProfile);

        if (false === $flush) {
            return;
        }

        $this->getEntityManager()->flush();
    }

    public function remove(OAuth2ClientProfile $oAuth2ClientProfile, bool $flush = false): void
    {
        $this->getEntityManager()->remove($oAuth2ClientProfile);

        if (false === $flush) {
            return;
        }

        $this->getEntityManager()->flush();
    }
}
