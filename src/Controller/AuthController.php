<?php

namespace App\Controller;

use App\Annotation\Post;
use App\Protobuf\Generated\SignInUserInput;
use App\Service\AuthGRPCService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    public function __construct(private AuthGRPCService $authGRPCService, private SerializerInterface $serializer)
    {
    }

    #[Post('/user/auth', name: 'auth_user')]
    public function auth(Request $request)
    {
        $data = $request->getContent();
        $signInInput = $this->serializer->deserialize($data, SignInUserInput::class, 'json');
        dd($signInInput);
        $parameters = json_decode($request->getContent(), true);

        $signInResponse = $this->authGRPCService->getAuthTokens($parameters['username'], $parameters['password']);

        return new JsonResponse([
            'accessToken' => $signInResponse->getAccessToken(),
            'refreshToken' => $signInResponse->getRefreshToken(),
        ], Response::HTTP_OK);
    }
}
