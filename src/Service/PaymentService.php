<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentProvider;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\Workflow\WorkflowInterface;

use function assert;

final readonly class PaymentService
{
    public function __construct(
        private WorkflowInterface $paymentProcessing,
        private Security $security,
        private PaymentRepository $paymentRepository,
    ) {}

    public function createPayment(Order $order, PaymentProvider $paymentType): void
    {
        $payment = new Payment(
            userId: $this->getUser()->getId(),
            amount: $order->getTotal(),
            operationType: $paymentType,
            operationNumber: UuidV7::generate(),
        );
        $order->addPayment($payment);

        $this->save($payment);
    }

    public function save(Payment $payment): void
    {
        $this->paymentRepository->save($payment);
    }

    public function confirmPayment(Order $order): void
    {
        $payment = $order->getLastPayment();
        assert($payment instanceof Payment);

        if (true === $this->paymentProcessing->can($payment, 'to_confirm')) {
            $this->paymentProcessing->apply($payment, 'to_confirm');
        }

        $this->paymentRepository->save($payment);
    }

    private function getUser(): ShopUserInterface
    {
        $user = $this->security->getUser();
        assert($user instanceof ShopUserInterface);

        return $user;
    }
}
