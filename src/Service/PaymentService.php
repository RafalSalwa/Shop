<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentProvider;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Workflow\WorkflowInterface;

use function assert;
use function is_subclass_of;

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
            operationNumber: Uuid::v7()->generate(),
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
        if (true === $this->paymentProcessing->can($payment, 'to_confirm')) {
            $this->paymentProcessing->apply($payment, 'to_confirm');
        }

        $this->paymentRepository->save($payment);
    }

    private function getUser(): ShopUserInterface
    {
        $user = $this->security->getUser();
        assert(is_subclass_of($user, ShopUserInterface::class));

        return $user;
    }
}
