<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Payment;
use App\Enum\PaymentProvider;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

final readonly class PaymentService
{
    public function __construct(
        private WorkflowInterface $paymentProcessing,
        private Security $security,
        private PaymentRepository $paymentRepository,
    ) {
    }

    public function createPayment(Order $order, PaymentProvider $paymentType): Payment
    {
        $payment = new Payment();
        $this->paymentProcessing->getMarking($payment);

        $payment->setUserId($this->security->getUser()->getId());
        $payment->setAmount($order->getTotal());
        $payment->setOperationType($paymentType);
        $order->addPayment($payment);

        $this->save($payment);

        return $payment;
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
}
