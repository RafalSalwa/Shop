<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function explode;
use function implode;

#[AsCommand(name: 'auth:client:create', description: 'creates auth client')]
class CreateAuthClientCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $clientName = 'Test Client';
        $clientId = 'testclient';
        $clientSecret = 'testpass';
        $clientDescription = 'Test Client App';
        $scopes = ['profile', 'email', 'cart'];
        $grantTypes = ['authorization_code', 'client_credentials ', 'refresh_token'];
        $redirectUris = explode(',', 'https://interview.local/callback');

        $user = $this->em->getRepository(User::class)->findOneBy(
            ['user_id' => 1],
        );
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        //        $this->em->persist($user);
        //        $this->em->flush();

        // Create the client
        $conn = $this->em->getConnection();
        $conn->beginTransaction();

        try {
            $conn->insert(
                'oauth2_client',
                [
                    'identifier' => $clientId,
                    'secret' => $clientSecret,
                    'name' => $clientName,
                    'redirect_uris' => implode(' ', $redirectUris),
                    'grants' => implode(' ', $grantTypes),
                    'scopes' => implode(' ', $scopes),
                    'active' => 1,
                    'allow_plain_text_pkce' => 0,
                ],
            );

            $conn->insert(
                'oauth2_client_profile',
                [
                    'id' => 1,
                    'client_id' => $clientId,
                    'name' => $clientDescription,
                ],
            );
            $conn->commit();
        } catch (Throwable) {
            $conn->rollBack();
        }
        $io->success('Bootstrap complete.');

        return Command::SUCCESS;
    }
}
