<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\SignUpUserInput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SignUpUserInput::class)]
class SignUpUserInputTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $input = new SignUpUserInput();
        $email = 'test@example.com';

        $input->setEmail($email);

        $this->assertEquals($email, $input->getEmail());
    }

    public function testGetSetPassword(): void
    {
        $input = new SignUpUserInput();
        $password = 'password123';

        $input->setPassword($password);

        $this->assertEquals($password, $input->getPassword());
    }

    public function testGetSetPasswordConfirm(): void
    {
        $input = new SignUpUserInput();
        $passwordConfirm = 'password123';

        $input->setPasswordConfirm($passwordConfirm);

        $this->assertEquals($passwordConfirm, $input->getPasswordConfirm());
    }

    public function testDefaultValues(): void
    {
        $input = new SignUpUserInput();

        $this->assertEquals('', $input->getEmail());
        $this->assertEquals('', $input->getPassword());
        $this->assertEquals('', $input->getPasswordConfirm());
    }
}
