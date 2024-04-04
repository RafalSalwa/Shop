<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Validation;

use function count;

final readonly class EmailAddress
{
    private const INVALID_EMAIL = 'Invalid email address';

    public function __construct(private string $email)
    {
        $validatorBuilder = Validation::createValidatorBuilder();
        $validator = $validatorBuilder->setConstraintValidatorFactory(
            new ConstraintValidatorFactory(
                [
                    EmailValidator::class => new EmailValidator(Email::VALIDATION_MODE_HTML5),
                ],
            ),
        )->getValidator();

        $emailConstraint = new Email(mode: Email::VALIDATION_MODE_STRICT);
        $emailConstraint->message = self::INVALID_EMAIL;

        $errors = $validator->validate($email, $emailConstraint);
        if (count($errors) > 0) {
            throw new InvalidArgumentException(message: self::INVALID_EMAIL);
        }
    }

    public function toString(): string
    {
        return $this->email;
    }
}
