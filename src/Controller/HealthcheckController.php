<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route(path: '/healthcheck', name: 'healthcheck_', methods: ['GET'])]
final class HealthcheckController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function healthcheck(): JsonResponse
    {
        return new JsonResponse('Ok');
    }
}
