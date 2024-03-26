<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\Contracts\AuthenticationExceptionInterface;
use App\Exception\Contracts\RegistrationExceptionInterface;
use App\Form\RegistrationConfirmationFormType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Security\AuthApiFormAuthenticator;
use App\Security\AuthApiUserProvider;
use App\Security\Contracts\ShopUserAuthenticatorInterface;
use App\Security\Contracts\UserRegistrarInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/register', name: 'register_', methods: ['GET', 'POST'])]
final class RegistrationController extends AbstractShopController
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
            try {
                $userRegistrar->confirm($form->get('confirmationCode')->getData());

                return $this->redirectToRoute(
                    'register_thank_you',
                    ['verificationCode' => $form->get('confirmationCode')->getData()],
                );
            } catch (AuthenticationExceptionInterface $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->render(
            'registration/confirm.html.twig',
            [
                'confirmationForm' => $form->createView(),
            ],
        );
    }

    #[Route(path: '/resend/', name: 'resend_confirmmation_email')]
    public function resendConfirmationEmail(Request $request, UserRegistrarInterface $userRegistrar): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if (true === $form->isSubmitted() && true === $form->isValid()) {
            try {
                $userRegistrar->sendVerificationCode($form->get('email')->getData());

                return $this->redirectToRoute(
                    'register_thank_you',
                    ['verificationCode' => $form->get('confirmationCode')->getData()],
                );
            } catch (AuthenticationExceptionInterface $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
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
        string $verificationCode,
        ShopUserAuthenticatorInterface $authenticator,
        AuthApiFormAuthenticator $authApiAuthenticator,
        AuthApiUserProvider $provider,
        Security $security,
    ): Response {
        $user = $authenticator->authenticateWithAuthCode($verificationCode);
        $user = $provider->loadUserByIdentifier($user->getUserIdentifier());

        $security->login(user: $user, authenticatorName: $authApiAuthenticator::class);

        return $this->render('registration/thank_you.html.twig');
    }
}
