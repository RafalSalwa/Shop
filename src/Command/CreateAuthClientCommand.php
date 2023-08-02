<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'auth:client:create',
    description: 'creates auth client',
)]
class CreateAuthClientCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $clientName = 'Test Client';
        $clientId = 'testclient';
        $clientSecret = 'testpass';
        $clientDescription = 'Test Client App';
        $scopes = ['profile', 'email'];
        $grantTypes = ['authorization_code', 'refresh_token'];
        $redirectUris = explode(',', "https://interview.local/callback");

        $user = $this->em->getRepository(User::class)->findOneBy(['user_id' => 1]);
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        // Create the client
        $conn = $this->em->getConnection();
        $conn->insert('oauth2_client', [
            'identifier' => $clientId,
            'secret' => $clientSecret,
            'name' => $clientName,
            'redirect_uris' => implode(' ', $redirectUris),
            'grants' => implode(' ', $grantTypes),
            'scopes' => implode(' ', $scopes),
            'active' => 1,
            'allow_plain_text_pkce' => 0,
        ]);

        $conn->insert('oauth2_client_profile', [
            'client_id' => $clientId,
            'name' => $clientDescription,
        ]);


        $io->success('Bootstrap complete.');

        return Command::SUCCESS;
    }
}