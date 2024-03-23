<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class UserAlreadyExistsException extends Exception implements AuthenticationExceptionInterface
{
}
