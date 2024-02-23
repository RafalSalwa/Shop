<?php

declare(strict_types=1);

namespace App\Controller;

use App\Annotation\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/healthcheck', name: 'healthcheck_', methods: ['GET'])]
final class HealthcheckController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function healthcheck(): JsonResponse
    {
        return new JsonResponse('Ok');
    }
}
