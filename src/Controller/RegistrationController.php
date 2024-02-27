<?php

declare(strict_types=1);

namespace App\Controller;

use App\Client\AuthApi\AuthApiClient;
use App\Client\AuthClientInterface;
use App\Exception\Registration\RegistrationExceptionInterface;
use App\Form\RegistrationConfirmationFormType;
use App\Form\RegistrationFormType;
use App\Security\AuthApiAuthenticator;
use App\Security\Registration\UserRegistrarInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[asController]
#[Route(path: '/register', name: 'register_', methods: ['GET', 'POST'])]
final class RegistrationController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function register(Request $request, UserRegistrarInterface $authApiUserRegistrar): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            try {
                $email = $form->get('email')->getData();
                $password = $form->get('password')->getData();

                $authApiUserRegistrar->register($email, $password);
                $authApiUserRegistrar->sendVerificationCode($email);

                return $this->redirectToRoute('register_confirm_email');
            } catch (RegistrationExceptionInterface $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/confirm/', name: 'confirm_email')]
    public function confirmUserEmail(Request $request, UserRegistrarInterface $userRegistrar): Response
    {
        $form = $this->createForm(RegistrationConfirmationFormType::class);
        $form->handleRequest($request);

        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $userRegistrar->confirm($form->get('confirmationCode')->getData());

            return $this->redirectToRoute(
                'register_thank_you',
                ['verificationCode' => $form->get('confirmationCode')->getData()],
            );
        }

        return $this->render(
            'registration/confirm.html.twig',
            [
                'confirmationForm' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/verify/{verificationCode}', name: 'verify_email')]
    public function verifyUserEmail(string $verificationCode, UserRegistrarInterface $userRegistrar): Response
    {
        $userRegistrar->confirm($verificationCode);

        return $this->redirectToRoute(
            'register_thank_you',
            ['verificationCode' => $verificationCode],
        );
    }

    #[Route(path: '/thank_you/{verificationCode}', name: 'thank_you')]
    public function thankYouPage(
        Request $request,
        string $verificationCode,
        UserAuthenticatorInterface $userAuthenticator,
        AuthApiAuthenticator $authApiAuthenticator,
        AuthClientInterface $authClient,
    ): Response {
        $user = $authClient->getByVerificationCode($verificationCode);

        $userAuthenticator->authenticateUser($user, $authApiAuthenticator, $request);

        return $this->render('registration/thank_you.html.twig');
    }
}
