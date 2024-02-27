<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[asController]
#[Route(path: '/login', name: 'login_')]
final class LoginController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('app_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/index.html.twig',
            [
                'error' => $error,
                'last_username' => $lastUsername,
            ],
        );
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(Security $security): Response
    {
        $security->logout();

        return $this->redirectToRoute('login_index');
    }
}
