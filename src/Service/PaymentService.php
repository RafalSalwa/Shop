<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class PaymentService
{
    public function __construct(private readonly WorkflowInterface $workflow, private readonly Security $security, private readonly PaymentRepository $paymentRepository)
    {
    }

    public function createPendingPayment(Order $order): Payment
    {
        $payment = $order->getLastPayment();
        if (!$payment) {
            $payment = new Payment();
        }
        $this->workflow->getMarking($payment);
        $payment->setUser($this->security->getUser());
        $payment->setAmount($order->getAmount());
        $order->addPayment($payment);

        return $payment;
    }

    public function confirmPayment(Payment $payment)
    {
        if ($this->workflow->can($payment, 'to_confirm')) {
            $this->workflow->apply($payment, 'to_confirm');
        }
        $this->paymentRepository->save($payment);
    }

    public function save(Payment $payment)
    {
        $this->paymentRepository->save($payment);
    }
}
