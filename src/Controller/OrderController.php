<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\PaymentType;
use App\Repository\OrderRepository;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class OrderController extends AbstractController
{
    #[Route('/order/create/', name: 'order_create_pending')]
    public function createPendingOrder(
        CartService $cartService,
        OrderService $orderService,
        PaymentService $paymentService,
    ): Response {
        $cart = $cartService->getCurrentCart();
        $pending = $orderService->createPending($cart);
        $paymentService->createPendingPayment($pending);

        if ($pending->getId() !== 0) {
            $cartService->clearCart();
        }

        return $this->redirectToRoute('order_show', [
            'id' => $pending->getId(),
        ]);
    }

    #[Route('/order/pending/{id}', name: 'order_show')]
    public function pending(
        Request $request,
        Order $order,
        OrderService $orderService,
        PaymentService $paymentService,
        CartService $cartService
    ): Response {
        $this->denyAccessUnlessGranted('view', $order, 'Access denied: You can only view pending orders.');

        $payment = $order->getLastPayment();
        $form = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $paymentService->confirmPayment($payment);
                $orderService->confirmOrder($order);

                $cartService->confirmCart();

                $cartService->clearCart();

                return $this->redirectToRoute('order_summary', [
                    'id' => $order->getId(),
                ]);
            }
            if ($form->get('no')->isClicked()) {
                return $this->redirectToRoute('order_index', [
                    'page' => 1,
                ]);
            }
        }

        return $this->render('order/payment.html.twig', [
            'order' => $order,
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/summary/{id}', name: 'order_summary')]
    public function summaryOrder(
        int $id,
        OrderRepository $orderRepository,
        OrderService $orderService,
        TaxCalculator $taxCalculator
    ): Response {
        $order = $orderRepository->fetchOrderDetails($id);

        $orderService->proceedSubscriptionsIfAny($order);
        $orderService->deserializeOrderItems($order);

        $taxCalculator->calculateOrderTax($order);

        return $this->render('order/summary.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/orders/{page<\d+>}', name: 'order_index')]
    public function index(int $page, #[CurrentUser] User $user, OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->fetchOrders($user, $page);

        return $this->render('order/index.html.twig', [
            'paginator' => $orders,
        ]);
    }

    #[Route('/order/{id<\d+>}', name: 'order_details')]
    public function show(int $id, OrderRepository $orderRepository, OrderService $orderService): Response
    {
        $order = $orderRepository->fetchOrderDetails($id);
        $orderService->deserializeOrderItems($order);

        return $this->render('order/details.html.twig', [
            'order' => $order,
        ]);
    }
}
