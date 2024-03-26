<?php

declare(strict_types=1);

namespace App\Controller\GRPC;

use App\Controller\AbstractShopController;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\TokenType;
use App\Form\UserVerifyCodeType;
use App\Model\GRPC\VerificationCodeRequest;
use App\Model\SignInUserInput;
use App\Model\SignUpUserInput;
use App\Service\GRPC\AuthApiGRPCService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/grpc', name: 'grpc_')]
final class AuthController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return $this->render('grpc/index.html.twig', []);
    }

    #[Route(path: '/user/sign_up', name: 'sign_up')]
    public function signUp(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $signUpUserInput = new SignUpUserInput();
        $form        = $this->createForm(SignUpType::class, $signUpUserInput);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $authGRPCService->signUpUser($signUpUserInput->getEmail(), $signUpUserInput->getPassword());
        }

        return $this->render(
            'grpc/sign_up.html.twig',
            [
                'form'              => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
            ],
        );
    }

    #[Route(path: '/user/confirm', name: 'user_confirm')]
    public function verifyUser(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $verificationCodeRequest = new VerificationCodeRequest();

        $form = $this->createForm(UserVerifyCodeType::class, $verificationCodeRequest);
        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $authGRPCService->confirmAccount($verificationCodeRequest->getVerificationCode());
        }

        return $this->render(
            'grpc/confirm.html.twig',
            [
                'form'          => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
                'api_user_response' => $authGRPCService->getUserCredentialsFromLastSignUp(),
            ],
        );
    }

    #[Route(path: '/user/sign_in', name: 'sign_in')]
    public function signIn(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $signInUserInput = new SignInUserInput();
        $form        = $this->createForm(SignInType::class, $signInUserInput);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $authGRPCService->signInUser($signInUserInput->getEmail(), $signInUserInput->getPassword());
        }

        return $this->render(
            'grpc/sign_in.html.twig',
            [
                'form'             => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
                'api_user_response' => $authGRPCService->getUserCredentialsFromLastSignUp(),
            ],
        );
    }

    #[Route(path: '/user/details', name: 'user_details')]
    public function getUserDetails(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $user = null;
        $form = $this->createForm(TokenType::class);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $token = $form->get('token')->getData();
            $user = $authGRPCService->getUserDetails($token);
        }

        return $this->render(
            'grpc/user_details.html.twig',
            [
                'form'             => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
                'api_user_response' => $authGRPCService->getUserCredentialsFromLastSignUp(),
                'user' => $user,
            ],
        );
    }
}
