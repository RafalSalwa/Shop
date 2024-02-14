<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UserRepository::class)]
#[UsesClass(User::class)]
final class UserRepositoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testMethodReturnsUserObject(): void
    {
        $user = new User();
        $user->setId(1);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->method('findOneByUsername')
            ->willReturn($user)
        ;

        $actualUser = $userRepository->findOneByUsername('interview');

        self::assertNotEmpty($actualUser);
        self::assertSame(1, $actualUser->getId());

        self::assertNull($userRepository->find('asd'));
    }
}
