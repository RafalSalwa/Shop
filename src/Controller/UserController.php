<?php

namespace App\Controller;

use App\Annotation\Get;
use App\Repository\UserRepository;
use App\Service\UserRESTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Get('/user/{id}', name: 'get_user')]
    public function get(int $id)
    {
       $user = $this->userRepository->findOneBy(["id"=>$id]);
       if($user){
           return new JsonResponse($user);
       }
        return new JsonResponse(null,Response::HTTP_NOT_FOUND);
    }

    #[Get('/user/rest/{id}', name: 'get_user_rest')]
    public function getRest(int $id, UserRESTService $userRESTService)
    {
        $userJson = $userRESTService->getUserDetails($id);
        return new JsonResponse($userJson, Response::HTTP_OK,[],true);
    }

}