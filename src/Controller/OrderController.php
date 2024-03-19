<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Form\PaymentType;
use App\Service\CalculatorService;
use App\Service\OrderService;
use App\Workflow\OrderWorkflow;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;
use function dd;

#[asController]
#[Route(path: '/order', name: 'order_', methods: ['GET', 'POST'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class OrderController extends AbstractShopController
{
    #[Route(path: '/create/', name: 'create_pending', methods: ['POST'])]
    public function createPendingOrder(Request $request, OrderWorkflow $orderWorkflow): Response
    {
        try {
            $paymentType = $request->request->get('payment');
            $pendingOrder = $orderWorkflow->createPendingOrder($paymentType);
        } catch (Throwable $e) {
            dd($e->getMessage(), $e->getCode(), $e->getTraceAsString());
        }

        return $this->redirectToRoute(
            'order_show',
            [
                'id' => $pendingOrder->getId(),
            ],
        );
    }

    #[Route(path: '/pending/{id}', name: 'show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function pending(Request $request, Order $order, OrderWorkflow $orderWorkflow): Response
    {
        $this->denyAccessUnlessGranted('view', $order, 'Access denied: You can only view pending orders.');

        $payment = $order->getLastPayment();
        $form    = $this->createForm(PaymentType::class, $payment);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid() && true === $form->get('yes')->isClicked()) {
            $orderWorkflow->confirmOrder($order);

            return $this->redirectToRoute('order_summary', ['id' => $order->getId()]);
        }

        return $this->render(
            'order/payment.html.twig',
            [
                'order'   => $order,
                'payment' => $payment,
                'form'    => $form->createView(),
            ],
        );
    }

    #[Route(path: '/summary/{id}', name: 'summary')]
    public function summaryOrder(
        int $id,
        OrderService $orderService,
        CalculatorService $cartCalculatorService,
    ): Response {
        $order = $orderService->fetchOrderDetails($id);

        return $this->render(
            'order/summary.html.twig',
            [
                'order' => $order,
                'summary' => $cartCalculatorService->calculateSummary($order->getNetAmount(), $order->getCoupon()),
            ],
        );
    }

    #[Route(path: '/orders/{page<\d+>}', name: 'index')]
    public function index(int $page, #[CurrentUser] User $user, OrderService $service): Response
    {
        $orders = $service->fetchOrders($user, $page);

        return $this->render(
            'order/index.html.twig',
            ['paginator' => $orders],
        );
    }

    #[Route(path: '/{id<\d+>}', name: 'details')]
    public function show(int $id, OrderService $orderService): Response
    {
        $order = $orderService->fetchOrderDetails($id);
        $orderService->deserializeOrderItems($order);

        return $this->render(
            'order/details.html.twig',
            ['order' => $order],
        );
    }
}
