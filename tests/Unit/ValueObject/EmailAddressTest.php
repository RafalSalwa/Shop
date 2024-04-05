<?php

declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use App\ValueObject\EmailAddress;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: EmailAddress::class)]
final class EmailAddressTest extends TestCase
{
    public function testValidEmailAddress(): void
    {
        $emailAddress = new EmailAddress('test@example.com');
        $this->assertSame('test@example.com', $emailAddress->toString());
    }

    public function testInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EmailAddress('invalid-email');
    }
}
