<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\EntityVerifyCode;
use App\Entity\SignInUserInput;
use App\Entity\SignUpUserInput;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserVerifyCodeType;
use App\Protobuf\Generated\SignInUserResponse;
use App\Service\AuthGRPCService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GRPCController extends AbstractController
{
    #[Route('/grpc', name: 'grpc_index')]
    public function index(): Response
    {
        return $this->render('grpc/index.html.twig', []);
    }

    #[Route('/grpc/user/create', name: 'grpc_user_create')]
    public function createUser(Request $request, AuthGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;

        $signUpUserInput = new SignUpUserInput();
        $form        = $this->createForm(SignUpType::class, $signUpUserInput);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->signUpUser(
                $signUpUserInput->getEmail(),
                $signUpUserInput->getPassword(),
            );
        }

        return $this->render(
            'grpc/sign_up.html.twig',
            [
                'form'          => $form->createView(),
                'grpc_status'   => $status,
                'grpc_response' => $grpcResponse,
            ],
        );
    }

    #[Route('/grpc/user/verify', name: 'grpc_user_verify')]
    public function verifyUser(Request $request, AuthGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $entityVerifyCode   = new EntityVerifyCode();

        if (true === $authGRPCService->getVcodeFromLastSignUp()) {
            $entityVerifyCode->setCode($authGRPCService->getVcodeFromLastSignUp());
        }

        $form = $this->createForm(UserVerifyCodeType::class, $entityVerifyCode);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->verifyCode($entityVerifyCode->getCode());
        }

        return $this->render(
            'grpc/verify.html.twig',
            [
                'form'          => $form->createView(),
                'grpc_status'   => $status,
                'grpc_response' => $grpcResponse,
            ],
        );
    }

    #[Route('/grpc/user/token', name: 'grpc_user_token')]
    public function getTokens(Request $request, AuthGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $lastSignUp   = $authGRPCService->getUserCredentialsFromLastSignUp();

        $signInUserInput = new SignInUserInput();
        $form        = $this->createForm(SignInType::class, $signInUserInput);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->signInUser(
                $signInUserInput->getUsername(),
                $signInUserInput->getPassword(),
            );

            if (0 === $status->code && $grpcResponse instanceof SignInUserResponse) {
                $arrUser = $authGRPCService->getUserCredentialsFromLastSignUp();

                $arrUser['access_token']  = $grpcResponse->getAccessToken();
                $arrUser['refresh_token'] = $grpcResponse->getRefreshToken();

                $authGRPCService->setUserCredentialsFromLastSignUp($arrUser);
            }
        }

        return $this->render(
            'grpc/sign_in.html.twig',
            [
                'form'             => $form->createView(),
                'grpc_status'      => $status,
                'grpc_response'    => $grpcResponse,
                'grpc_credentials' => $lastSignUp,
            ],
        );
    }

    #[Route('/grpc/user/details', name: 'grpc_user_get')]
    public function getUserDetails(Request $request, AuthGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $lastSignUp   = $authGRPCService->getUserCredentialsFromLastSignUp();

        $signInUserInput = new SignInUserInput();
        $form        = $this->createForm(SignInType::class, $signInUserInput);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->signInUser(
                $signInUserInput->getUsername(),
                $signInUserInput->getPassword(),
            );

            if (0 === $status->code && $grpcResponse instanceof SignInUserResponse) {
                $arrUser = $authGRPCService->getUserCredentialsFromLastSignUp();

                $arrUser['access_token']  = $grpcResponse->getAccessToken();
                $arrUser['refresh_token'] = $grpcResponse->getRefreshToken();

                $authGRPCService->setUserCredentialsFromLastSignUp($arrUser);
            }
        }

        return $this->render(
            'grpc/sign_in.html.twig',
            [
                'form'             => $form->createView(),
                'grpc_status'      => $status,
                'grpc_response'    => $grpcResponse,
                'grpc_credentials' => $lastSignUp,
            ],
        );
    }
}
