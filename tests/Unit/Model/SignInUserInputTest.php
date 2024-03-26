<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\SignInUserInput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SignInUserInput::class)]
final class SignInUserInputTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $signInUserInput = new SignInUserInput();
        $email = 'test@example.com';

        $signInUserInput->setEmail($email);

        $this->assertSame($email, $signInUserInput->getEmail());
    }

    public function testGetSetPassword(): void
    {
        $signInUserInput = new SignInUserInput();
        $password = 'password123';

        $signInUserInput->setPassword($password);

        $this->assertSame($password, $signInUserInput->getPassword());
    }

    public function testDefaultValues(): void
    {
        $signInUserInput = new SignInUserInput();

        $this->assertSame('', $signInUserInput->getEmail());
        $this->assertSame('', $signInUserInput->getPassword());
    }
}
