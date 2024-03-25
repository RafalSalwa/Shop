<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Contracts\ShopUserInterface;
use App\Entity\Order;
use App\Event\OrderConfirmedEvent;
use App\Exception\ItemNotFoundException;
use App\Factory\OrderItemFactory;
use App\Pagination\Paginator;
use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

use function assert;
use function is_subclass_of;

final readonly class OrderService
{
    public function __construct(
        private WorkflowInterface $orderProcessingStateMachine,
        private Security $security,
        private OrderRepository $orderRepository,
        private AddressBookService $addressBookService,
        private EventDispatcherInterface $eventDispatcher,
        private CalculatorService $calculatorService,
    ) {}

    public function createPending(Cart $cart): Order
    {
        $summary = $this->calculatorService->calculateSummary($cart->getTotalAmount(), $cart->getCoupon());

        $order = new Order(
            netAmount: $cart->getTotalAmount(),
            userId: $this->getUser()->getId(),
            shippingCost: $summary->getShipping(),
            total: $summary->getTotal(),
        );
        $this->orderProcessingStateMachine->getMarking($order);

        $order->applyCoupon($cart->getCoupon());

        foreach ($cart->getItems() as $cartItem) {
            $orderItem = OrderItemFactory::createFromCartItem($cartItem);
            $order->addItem($orderItem);
        }

        $this->assignDeliveryAddress($order);
        $this->orderRepository->save($order);

        return $order;
    }

    /** @throws ItemNotFoundException */
    private function assignDeliveryAddress(Order $order): void
    {
        $address = $this->addressBookService->getDefaultDeliveryAddress($order->getUserId());
        if (null === $address) {
            throw new ItemNotFoundException('There is no Address in AddressBook');
        }

        $order->setDeliveryAddress($address);
        $order->setBilingAddress($address);
    }

    public function confirmOrder(Order $order): void
    {
        if (true === $this->orderProcessingStateMachine->can($order, 'to_completed')) {
            $this->orderProcessingStateMachine->apply($order, 'to_completed');
        }

        $this->orderRepository->save($order);

        $orderConfirmedEvent = new OrderConfirmedEvent($order);
        $this->eventDispatcher->dispatch($orderConfirmedEvent);
    }

    public function fetchOrderDetails(int $id): ?Order
    {
        return $this->orderRepository->fetchOrderDetails($id);
    }

    /** @param array<string> $status */
    public function fetchOrders(
        int $userId,
        int $page = 1,
        array $status = [Order::COMPLETED, Order::CANCELLED],
    ): Paginator {
        return $this->orderRepository->fetchOrders($userId, $page, $status);
    }

    private function getUser(): ShopUserInterface
    {
        $user = $this->security->getUser();
        assert(is_subclass_of($user, ShopUserInterface::class));

        return $user;
    }
}
