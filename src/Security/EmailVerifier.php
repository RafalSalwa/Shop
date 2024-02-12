<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly MailerInterface $mailer,
        private UrlGeneratorInterface $router,
    ) {}

    public function sendEmailConfirmation(string $email, string $verificationCode): void
    {
        $templatedEmail = (new TemplatedEmail())
            ->from(new Address('noreply@interview.com', 'Interview Shop Bot'))
            ->to($email)
            ->subject('Please Confirm your Email')
            ->htmlTemplate('registration/email/confirmation_email.html.twig')
        ;

        $context = $templatedEmail->getContext();
        $context['signedUrl'] = $this->router->generate(
            'register_verify_email',
            [
                'verificationCode' => $verificationCode,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context['confirmationUrl'] = $this->router->generate(
            'register_confirm_email',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $context['verificationCode'] = $verificationCode;
        $templatedEmail->context($context);

        try {
            $this->mailer->send($templatedEmail);
        } catch (TransportExceptionInterface $e) {
            dd($e->getMessage());
        }
    }

    public function handleEmailConfirmation(Request $request): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri());
    }
}
