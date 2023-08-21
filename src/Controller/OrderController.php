<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Form\PaymentType;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/create/', name: 'order_create_pending')]
    public function createPendingOrder(CartService $cartService, OrderService $orderService): Response
    {
        $cart = $cartService->getCurrentCart();
        $order = $orderService->createPending($cart);
        if ($order->getId()) {
            $cartService->clearCart();
        }
        return $this->redirectToRoute("order_show", ["id" => $order->getId()]);
    }

    #[Route('/order/pending/{id}', name: 'order_show')]
    public function addToCart(
        int            $id,
        Request        $request,
        Order          $order,
        OrderService   $orderService,
        PaymentService $paymentService,
        CartService    $cartService
    ): Response
    {
        $payment = $paymentService->createPayment($order);
        $payment->setAmount($order->getAmount());

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $paymentService->confirmPayment($payment);
                $orderService->confirmOrder($order, $payment);
                $cartService->confirmCart();
                $cartService->clearCart();
                return $this->redirectToRoute("order_summary", [
                    "id" => $order->getId()
                ]);
            }
        }
        return $this->render('order/payment.html.twig', [
            'order' => $order,
            'payment' => $payment,
            'form' => $form->createView()
        ]);
    }

    #[Route('/order/summary/{id}', name: 'order_summary')]
    public function summaryOrder(int $id, Order $order): Response
    {
        return $this->render('order/summary.html.twig', [
            'order' => $order
        ]);
    }
}
