<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthcheckController extends AbstractController
{
    #[Get('/healthcheck', name: 'healthcheck')]
    public function healthcheck(): JsonResponse
    {
        return new JsonResponse('Ok');
    }
}
