<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Event\UserConfirmedEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: UserConfirmedEvent::class)]
final class UserConfirmedEventTest extends TestCase
{
    public function testGetter(): void
    {
        $userConfirmedEvent = new UserConfirmedEvent(1);
        $this->assertSame(1, $userConfirmedEvent->getUserId());
    }
}
