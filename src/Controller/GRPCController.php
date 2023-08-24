<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GRPCController extends AbstractController
{
    #[Route('/grpc', name: 'grpc_index')]
    public function index(Request $request): Response
    {
        return $this->render('grpc/index.html.twig', [
        ]);
    }

    #[Route('/grpc/user/create', name: 'grpc_user_create')]
    public function createUser(Request $request): Response
    {
        return $this->render('grpc/index.html.twig', [
        ]);
    }
}