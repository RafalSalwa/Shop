<?php

namespace App\Controller;

use App\Annotation\Post;

use App\Service\AuthGRPCService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{

    private AuthGRPCService $authGRPCService;
    public function __construct(AuthGRPCService $authGRPCService)
    {
        $this->authGRPCService = $authGRPCService;
    }

    #[Post('/user/auth', name: 'auth_user')]
    public function auth( Request $request)
    {
        $parameters = json_decode($request->getContent(), true);

        $signInResponse = $this->authGRPCService->getAuthTokens($parameters['username'], $parameters['password']);

        return new JsonResponse([
            "accessToken"=>$signInResponse->getAccessToken(),
            "refreshToken"=>$signInResponse->getRefreshToken(),
            ],Response::HTTP_OK);
    }
}