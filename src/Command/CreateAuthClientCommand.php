<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function implode;

#[AsCommand(name: 'auth:client:create', description: 'creates auth client')]
final class CreateAuthClientCommand extends AbstractSymfonyCommand
{
    /**
     * @param array<string> $scopes
     * @param array<string> $grantTypes
     * @param array<string> $redirectUris
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $clientId = 'testclient',
        private readonly array $scopes = ['profile', 'email', 'cart'],
        private readonly array $grantTypes = ['authorization_code', 'client_credentials ', 'refresh_token'],
        private readonly array $redirectUris = ['https://interview.local/callback'],
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $user = new User(id: 1, email: 'test@test.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);

        $conn = $this->entityManager->getConnection();

        try {
            $conn->beginTransaction();
            $conn->insert(
                'oauth2_client',
                [
                    'identifier' => $this->clientId,
                    'secret' => 'testclientsecret',
                    'name' => 'Test Client',
                    'redirect_uris' => implode(' ', $this->redirectUris),
                    'grants' => implode(' ', $this->grantTypes),
                    'scopes' => implode(' ', $this->scopes),
                    'active' => 1,
                    'allow_plain_text_pkce' => 0,
                ],
            );

            $conn->insert(
                'oauth2_client_profile',
                [
                    'id' => 1,
                    'client_id' => $this->clientId,
                    'name' => 'Test Client App',
                ],
            );
            $conn->commit();
        } catch (Throwable) {
            $conn->rollBack();
        }

        $symfonyStyle->success('Bootstrap complete.');
        $this->render('<info>Bootstrap complete</info>');

        return Command::SUCCESS;
    }
}
