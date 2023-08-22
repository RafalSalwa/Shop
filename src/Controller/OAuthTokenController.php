<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OAuthTokenController extends AbstractController
{
    #[Route('/oauth/token', name: 'oauth_token_index')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        return $this->render('oauth_token/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
