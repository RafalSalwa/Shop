<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\OrderConfirmedEvent;
use App\Service\CalculatorService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

final readonly class OrderConfirmedListener implements EventSubscriberInterface
{
    public function __construct(
        private MailerInterface $mailer,
        private string $fromEmail,
        private CalculatorService $calculatorService,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [OrderConfirmedEvent::class => 'onOrderConfirmed'];
    }

    public function onOrderConfirmed(OrderConfirmedEvent $orderConfirmedEvent): void
    {
        $order = $orderConfirmedEvent->getOrder();

        $email = (new TemplatedEmail())
            ->from($this->fromEmail)
            ->to($this->fromEmail)
            ->subject('Order Confirmation')
            ->htmlTemplate('email/order_summary.html.twig')
            ->context(
                [
                    'order' => $order,
                    'summary' => $this->calculatorService->calculateSummary(
                        $order->getNetAmount(),
                        $order->getCoupon(),
                    ),
                ],
            )
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }
}
