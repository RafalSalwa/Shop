<?php

namespace App\Exception;

use App\Exception\AuthenticationExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerErrorException extends HttpException implements AuthenticationExceptionInterface
{

}