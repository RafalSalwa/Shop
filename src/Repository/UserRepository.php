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

/** @extends ServiceEntityRepository<User> */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserLoaderInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $passwordAuthenticatedUser, string $newHashedPassword): void {}

    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
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

    public function findOneByEmail(): void {}

    public function findOneByUsername(string $username)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
        ;

        return $queryBuilder->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function save(User $user): void
    {
        $this->getEntityManager()
            ->persist($user)
        ;
        $this->getEntityManager()
            ->flush()
        ;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return parent::getEntityManager();
    }
}
