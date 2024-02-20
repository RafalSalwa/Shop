<?php

namespace App\Exception;

use App\Exception\AuthenticationExceptionInterface;

class AuthApiRuntimeException extends \RuntimeException implements AuthenticationExceptionInterface
{

}