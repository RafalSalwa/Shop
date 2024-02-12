<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\StockDepletedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class StockDepletedListener implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [StockDepletedEvent::class => 'onStockDepleted'];
    }

    public function onStockDepleted(StockDepletedEvent $event): void
    {
        $productData = $event->getEventData();
        $email = (new Email())
            ->from('system@interview.com')
            ->to('system@interview.com')
            ->subject('[PHP] Stock depleted for product ' . $productData['name'] . ' with ID #' . $productData['id'])
            ->html('We need to restock :)');

        $this->mailer->send($email);
    }
}
