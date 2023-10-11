<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class PaymentService
{
    public function __construct(
        private readonly WorkflowInterface $paymentProcessing,
        private readonly Security $security,
        private readonly PaymentRepository $paymentRepository
    ) {
    }

    public function createPendingPayment(Order $order): Payment
    {
        $payment = $order->getLastPayment();
        if (!$payment) {
            $payment = new Payment();
        }
        $this->paymentProcessing->getMarking($payment);
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
        if ($this->paymentProcessing->can($payment, 'to_confirm')) {
            $this->paymentProcessing->apply($payment, 'to_confirm');
        }
        $this->paymentRepository->save($payment);
    }
}
