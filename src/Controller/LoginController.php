<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    private UserPasswordHasherInterface $passwordEncoder;
    private ManagerRegistry $em;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, ManagerRegistry $doctrine)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $doctrine;
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
        $response = $security->logout();
        $this->redirectToRoute("app_login");
    }

    private function createTestUser()
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['user_id' => 1]);
        $password = "interview";
        $hashedPassword = $this->passwordEncoder->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword)->setEmail("interview@interview.com")->setUsername("interview");
        $this->em->getManager()->persist($user);
        $this->em->getManager()->flush();
        dd($user, $hashedPassword);
    }
}
