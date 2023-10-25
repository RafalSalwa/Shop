<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testMethodReturnsUserObject(): void
    {
        $user = new User();
        $user->setId(1);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->expects($this->any())
            ->method('findOneByUsername')
            ->willReturn($user);

        $actualUser = $userRepository->findOneByUsername('interview');

        $this->assertNotEmpty($actualUser);
        self::assertSame(1, $actualUser->getId());

        self::assertNull($userRepository->find('asd'));
    }
}
