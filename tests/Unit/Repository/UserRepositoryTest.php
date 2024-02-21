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
 */
#[CoversClass(UserRepository::class)]
#[UsesClass(User::class)]
#[\PHPUnit\Framework\Attributes\CoversNothing]
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

        $this->assertNotEmpty($actualUser);
        $this->assertSame(1, $actualUser->getId());

        $this->assertNull($userRepository->find('asd'));
    }
}
