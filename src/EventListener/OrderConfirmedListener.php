<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\OrderConfirmedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class OrderConfirmedListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $fromEmail
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderConfirmedEvent::class => 'onOrderConfirmed',
        ];
    }

    public function onOrderConfirmed(OrderConfirmedEvent $event): void
    {
        $orderData = $event->getOrderData();

        $email = (new Email())
            ->from($this->fromEmail)
            ->to($this->fromEmail)
            ->subject('Order Confirmation')
            ->html('Thank you for your order. Here are the details: <pre>' . print_r($orderData, true) . '</pre>');
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }
}
