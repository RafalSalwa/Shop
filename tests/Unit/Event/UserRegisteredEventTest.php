<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Event\UserRegisteredEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: UserRegisteredEvent::class)]
final class UserRegisteredEventTest extends TestCase
{
    public function testGetter(): void
    {
        $userRegisteredEvent = new UserRegisteredEvent('test@test.com', 'asdf');
        $this->assertSame('test@test.com', $userRegisteredEvent->getEmail());
        $this->assertSame('asdf', $userRegisteredEvent->getConfirmationCode());
    }
}
