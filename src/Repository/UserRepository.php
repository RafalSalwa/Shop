<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use function filter_var;
use const FILTER_VALIDATE_EMAIL;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
    }

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        if (filter_var($identifier, \FILTER_VALIDATE_EMAIL)) {
            return $this->findOneBy(
                ['email' => $identifier],
            );
        }
        if (Uuid::isValid($identifier)) {
            return $this->findOneBy(
                [
                    'uuid' => Uuid::fromString($identifier)->toBinary(),
                ],
            );
        }

        return null;
    }

    public function findOneByEmail(): void
    {
    }

    public function findOneByUsername(string $username)
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user): void
    {
        $this->getEntityManager()
            ->persist($user);
        $this->getEntityManager()
            ->flush();
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return parent::getEntityManager();
    }
}
