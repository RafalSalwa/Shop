<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use App\Form\DecisionType;
use App\Service\CartService;
use App\Service\OrderService;
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
    public function addToCart(int $id, Request $request, Order $order): Response
    {
        $payment = new Payment();
        $payment->setAmount($order->getAmount());

        $form = $this->createForm(DecisionType::class, $payment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                dd("T");
            }
            if ($form->get('no')->isClicked()) {
                dd("N");
            }
        }
        return $this->render('order/payment.html.twig', [
            'order' => $order,
            'payment' => $payment,
            'form' => $form->createView()
        ]);
    }
}
