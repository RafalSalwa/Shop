<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\StockDepletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use function sprintf;

final readonly class StockDepletedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer)
    {}

    /** @return array<string, array{0: string, 1: int}|list<array{0: string, 1?: int}>|string> */
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
            ->subject(sprintf('[PHP] Stock depleted for product %s with ID # %d', $item->getName(), $item->getId()))
            ->html('We need to restock :)')
        ;

        $this->mailer->send($email);
    }
}
