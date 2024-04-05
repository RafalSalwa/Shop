<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Event\UserVerificationCodeRequestEvent;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: UserVerificationCodeRequestEvent::class)]
final class UserVerificationCodeRequestEventTest extends TestCase
{
    public function testGetter(): void
    {
        $userVerificationCodeRequestEvent = new UserVerificationCodeRequestEvent('test@test.com', 'asdf');
        $this->assertSame('test@test.com', $userVerificationCodeRequestEvent->getEmail());
        $this->assertSame('asdf', $userVerificationCodeRequestEvent->getConfirmationCode());
    }
}
