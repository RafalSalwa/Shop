<?php

namespace App\Controller;

use App\Annotation\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HealthcheckController extends AbstractController
{
    /**
     * @param Request $request
     */
    #[Get('/healthcheck', name: 'healthcheck')]
    public function healthcheck(): JsonResponse
    {
        return new JsonResponse('Ok');
    }
}


