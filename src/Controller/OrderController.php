<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\PaymentType;
use App\Repository\OrderRepository;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class OrderController extends AbstractController
{
    #[Route('/order/create/', name: 'order_create_pending')]
    public function createPendingOrder(CartService $cartService, OrderService $orderService, PaymentService $paymentService,): Response
    {
        $cart = $cartService->getCurrentCart();
        $order = $orderService->createPending($cart);
        $orderService->assignDeliveryAddress($order);
        $payment = $paymentService->createPayment($order);
        $paymentService->save($payment);

        if ($order->getId()) {
            $cartService->clearCart();
        }
        return $this->redirectToRoute("order_show", ["id" => $order->getId()]);
    }

    #[Route('/order/pending/{id}', name: 'order_show')]
    public function addToCart(
        Request        $request,
        Order          $order,
        OrderService   $orderService,
        PaymentService $paymentService,
        CartService    $cartService
    ): Response
    {
        $payment = $order->getLastPayment();
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

    #[Route('/orders/{page<\d+>}', name: 'order_index')]
    public function index(int $page, #[CurrentUser] User $user, OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->fetchOrders($user, $page);
        return $this->render('order/index.html.twig', [
            'paginator' => $orders
        ]);
    }

    #[Route('/order/{id<\d+>}', name: 'order_details')]
    public function show(int $id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->fetchOrderDetails($id);
        return $this->render('order/index.html.twig', [
            'orders' => $order
        ]);
    }
}
