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

    public function createPayment(Order $order): Payment
    {
        $payment = $order->getLastPayment();
        if (!$payment) {
            $payment = new Payment();
        }
        $this->workflow->getMarking($payment);
        $payment->setUser($this->security->getUser());
        $order->addPayment($payment);
        return $payment;
    }

    public function confirmPayment(Payment $payment)
    {
        $this->workflow->apply($payment, 'to_confirm');
        $this->paymentRepository->save($payment);
    }
}
