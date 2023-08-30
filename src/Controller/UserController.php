<?php

namespace App\Controller;

use App\Annotation\Get;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    #[Get('/user/{id}', name: 'get_user')]
    public function get(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findOneBy(["id" => $id]);
        if ($user) {
            return new JsonResponse($user);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

}