<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\StockDepletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final readonly class StockDepletedListener implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer)
    {}

    /** @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>> */
    public static function getSubscribedEvents(): array
    {
        return [StockDepletedEvent::class => 'onStockDepleted'];
    }

    public function onStockDepleted(StockDepletedEvent $stockDepletedEvent): void
    {
        $item = $stockDepletedEvent->getItem();
        $email = (new Email())
            ->from('system@interview.com')
            ->to('system@interview.com')
            ->subject('[PHP] Stock depleted for product ' . $item->getName() . ' with ID #' . $item->getId())
            ->html('We need to restock :)')
        ;

        $this->mailer->send($email);
    }
}
