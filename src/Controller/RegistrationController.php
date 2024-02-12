<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\RegistrationConfirmationFormType;
use App\Form\RegistrationFormType;
use App\Model\User;
use App\Security\AuthApiAuthenticator;
use App\Security\AuthApiRegisterer;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[Route('/register', name: 'register_')]
class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier) {}

    #[Route('/', name: 'index')]
    public function register(
        Request $request,
        AuthApiRegisterer $authApiRegisterer,
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $verificationCode = $authApiRegisterer->register($user->getEmail(), $user->getPassword());
            $this->emailVerifier->sendEmailConfirmation($user->getEmail(), $verificationCode);

            return $this->redirectToRoute('register_confirm_email');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ],
        );
    }

    #[Route('/confirm/', name: 'confirm_email')]
    public function confirmUserEmail(
        Request $request,
        AuthApiRegisterer $authApiRegisterer
    ): Response {
        $form = $this->createForm(RegistrationConfirmationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authApiRegisterer->confirmAccount($form->get('confirmationCode')->getData());

            return $this->redirectToRoute(
                'register_thank_you',
                ['verificationCode' => $form->get('confirmationCode')->getData()]
            );
        }

        return $this->render(
            'registration/confirm.html.twig',
            [
                'confirmationForm' => $form->createView(),
            ],
        );
    }

    #[Route('/verify/{verificationCode}', name: 'verify_email')]
    public function verifyUserEmail(string $verificationCode, AuthApiRegisterer $authApiRegisterer): Response
    {
        $authApiRegisterer->confirmAccount($verificationCode);

        return $this->redirectToRoute(
            'register_thank_you',
            ['verificationCode' => $verificationCode]
        );
    }

    #[Route('/thank_you/{verificationCode}', name: 'thank_you')]
    public function thankYouPage(
        Request $request,
        string $verificationCode,
        UserAuthenticatorInterface $userAuthenticator,
        AuthApiAuthenticator $authApiAuthenticator,
        AuthApiRegisterer $authApiRegisterer
    ): Response {
        $user = $authApiRegisterer->getUserByCode($verificationCode);

        $userAuthenticator->authenticateUser(
            $user,
            $authApiAuthenticator,
            $request
        );

        return $this->render('registration/thank_you.html.twig');
    }
}
