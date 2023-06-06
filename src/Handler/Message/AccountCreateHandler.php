<?php

declare(strict_types=1);

namespace App\Handler\Message;

use App\Message\AccountCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AccountCreateHandler
{
    public function __construct(
        protected LoggerInterface $logger,
    )
    {
    }

    public function __invoke(AccountCreated $statusUpdate): void
    {
        $statusDescription = $statusUpdate->getStatus();

        $this->logger->warning('APP1: {STATUS_UPDATE} - ' . $statusDescription);

        // the rest of business logic, i.e. sending email to user
        // $this->emailService->email()
    }
}
