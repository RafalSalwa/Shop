<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PaymentService
{
    private Security $security;
    private WorkflowInterface $workflow;
    private PaymentRepository $paymentRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        Security                 $security,
        WorkflowInterface        $paymentProcessing,
        PaymentRepository        $paymentRepository,
        EventDispatcherInterface $eventDispatcher,
    )
    {
        $this->security = $security;
        $this->workflow = $paymentProcessing;
        $this->eventDispatcher = $eventDispatcher;
        $this->paymentRepository = $paymentRepository;
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
