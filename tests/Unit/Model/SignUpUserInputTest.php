<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\SignUpUserInput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SignUpUserInput::class)]
final class SignUpUserInputTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $signUpUserInput = new SignUpUserInput();
        $email = 'test@example.com';

        $signUpUserInput->setEmail($email);

        $this->assertSame($email, $signUpUserInput->getEmail());
    }

    public function testGetSetPassword(): void
    {
        $signUpUserInput = new SignUpUserInput();
        $password = 'password123';

        $signUpUserInput->setPassword($password);

        $this->assertSame($password, $signUpUserInput->getPassword());
    }

    public function testGetSetPasswordConfirm(): void
    {
        $signUpUserInput = new SignUpUserInput();
        $passwordConfirm = 'password123';

        $signUpUserInput->setPasswordConfirm($passwordConfirm);

        $this->assertSame($passwordConfirm, $signUpUserInput->getPasswordConfirm());
    }

    public function testDefaultValues(): void
    {
        $signUpUserInput = new SignUpUserInput();

        $this->assertSame('', $signUpUserInput->getEmail());
        $this->assertSame('', $signUpUserInput->getPassword());
        $this->assertSame('', $signUpUserInput->getPasswordConfirm());
    }
}
