<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/', name: 'app_', methods: ['GET'])]
final class IndexController extends AbstractShopController
{
    #[Route(path: '', name: 'index', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }
}
