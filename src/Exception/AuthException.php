<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class AuthException extends Exception implements AuthenticationExceptionInterface
{
}
