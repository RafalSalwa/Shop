<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Event\OrderConfirmedEvent;
use App\Factory\OrderItemFactory;
use App\Model\User;
use App\Pagination\Paginator;
use App\Repository\OrderRepository;
use Doctrine\ORM\NonUniqueResultException;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class OrderService
{
    public function __construct(
        private WorkflowInterface $orderProcessingStateMachine,
        private Security $security,
        private OrderRepository $orderRepository,
        private AddressBookService $addressBookService,
        private EventDispatcherInterface $eventDispatcher,
        private CartCalculatorService $calculatorService,
    ) {}

    public function createPending(Cart $cart): Order
    {
        $order = new Order();
        $this->orderProcessingStateMachine->getMarking($order);

        $order->setUserId($this->security->getUser()->getId());
        $summary = $this->calculatorService->calculateSummary($cart->getTotalAmount(), $cart->getCoupon());
        $order->setTotal($summary->getTotal());

        foreach ($cart->getItems() as $cartItem) {
            $orderItem = OrderItemFactory::createFromCartItem($cartItem);
            $order->addItem($orderItem);
        }

        $this->assignDeliveryAddress($order);
        $this->orderRepository->save($order);

        return $order;
    }

    /** @throws NonUniqueResultException */
    private function assignDeliveryAddress(Order $order): void
    {
        $address = $this->addressBookService->getDefaultDeliveryAddress($order->getUserId());
        if (null === $address) {
            throw new RuntimeException('There is no Address in AddressBook');
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

        $orderConfirmedEvent = new OrderConfirmedEvent($order->getId());
        $this->eventDispatcher->dispatch($orderConfirmedEvent);
    }

    public function fetchOrderDetails(int $id): ?Order
    {
        return $this->orderRepository->fetchOrderDetails($id);
    }

    public function fetchOrders(User $user, int $page): Paginator
    {
        return $this->orderRepository->fetchOrders($user, $page);
    }
}
