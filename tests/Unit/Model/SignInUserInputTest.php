<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model;

use App\Model\SignInUserInput;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: SignInUserInput::class)]
class SignInUserInputTest extends TestCase
{
    public function testGetSetEmail(): void
    {
        $input = new SignInUserInput();
        $email = 'test@example.com';

        $input->setEmail($email);

        $this->assertEquals($email, $input->getEmail());
    }

    public function testGetSetPassword(): void
    {
        $input = new SignInUserInput();
        $password = 'password123';

        $input->setPassword($password);

        $this->assertEquals($password, $input->getPassword());
    }

    public function testDefaultValues(): void
    {
        $input = new SignInUserInput();

        $this->assertEquals('', $input->getEmail());
        $this->assertEquals('', $input->getPassword());
    }
}
