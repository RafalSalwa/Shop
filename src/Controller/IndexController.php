<?php

declare(strict_types=1);

namespace App\Controller;

use App\Cache\Values;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', defaults: ['_format' => 'html'], methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    #[Cache(maxage: Values::MAX_AGE, public: true, mustRevalidate: true)]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }
}
