<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(UserRepository::class)]
#[UsesClass(User::class)]
class UserRepositoryTest extends TestCase
{
    public function testMethodReturnsUserObject(): void
    {
        $user = new User();
        $user->setId(1);

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->method('findOneByUsername')
            ->willReturn($user);

        $actualUser = $userRepository->findOneByUsername('interview');

        $this->assertNotEmpty($actualUser);
        $this->assertSame(1, $actualUser->getId());

        $this->assertNull($userRepository->find('asd'));
    }
}
