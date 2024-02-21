<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

readonly class PaymentService
{
    public function __construct(
        private WorkflowInterface $workflow,
        private Security $security,
        private PaymentRepository $paymentRepository,
    ) {}

    public function createPendingPayment(Order $order): Payment
    {
        $payment = $order->getLastPayment();
        if (!$payment instanceof \App\Entity\Payment) {
            $payment = new Payment();
        }

        $this->workflow->getMarking($payment);
        $payment->setUser($this->security->getUser());
        $payment->setAmount($order->getAmount());

        $order->addPayment($payment);

        $this->save($payment);

        return $payment;
    }

    public function save(Payment $payment): void
    {
        $this->paymentRepository->save($payment);
    }

    public function confirmPayment(Payment $payment): void
    {
        if ($this->workflow->can($payment, 'to_confirm')) {
            $this->workflow->apply($payment, 'to_confirm');
        }

        $this->paymentRepository->save($payment);
    }
}
