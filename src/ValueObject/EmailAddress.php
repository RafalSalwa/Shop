<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use function count;

final class EmailAddress
{
    private const INVALID_EMAIL = 'Invalid email address';

    private string $email;

    public function __construct(string $emailAddress)
    {
        $validator = Validation::createValidator();
        $emailConstraint = new Assert\Email(mode: Email::VALIDATION_MODE_STRICT);
        $emailConstraint->message = self::INVALID_EMAIL;

        $errors = $validator->validate($emailAddress, $emailConstraint);
        if (count($errors) > 0) {
            throw new InvalidArgumentException(message: self::INVALID_EMAIL);
        }
        $this->email = $emailAddress;
    }

    public function toString(): string
    {
        return $this->email;
    }

    public function value(): string
    {
        return $this->email;
    }
}
