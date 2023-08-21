<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Event\OrderConfirmedEvent;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderService
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private WorkflowInterface $workflow;
    private SerializerInterface $serializer;
    private CartCalculator $cartCalculator;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntityManagerInterface   $entityManager,
        Security                 $security,
        WorkflowInterface        $orderProcessing,
        SerializerInterface      $serializer,
        CartCalculator           $cartCalculator,
        EventDispatcherInterface $eventDispatcher,
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->workflow = $orderProcessing;
        $this->serializer = $serializer;
        $this->cartCalculator = $cartCalculator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createPending(Cart $cart): Order
    {
        $order = new Order();
        $this->workflow->getMarking($order);
        $order->setAmount($this->cartCalculator->calculateTotal($cart));

        $user = $this->security->getUser();
        /** @var OrderRepository $repository */
        $repository = $this->entityManager->getRepository(Order::class);
        foreach ($cart->getItems() as $item) {
            /** @var CartItem $itemEntity */
            $itemEntity = $item->getDestinationEntity();
            $serialized = $this->serializer->serialize($itemEntity, 'json');

            $orderItem = new OrderItem();
            $orderItem->setCartItem($serialized);

            $order->addItem($orderItem);
            $order->setUser($user);
        }
        $repository->save($order);
        return $order;
    }

    public function confirmOrder(Order $order, $payment)
    {
        $order->addPayment($payment);
        $this->workflow->apply($order, "to_completed");
        /** @var OrderRepository $repository */
        $repository = $this->entityManager->getRepository(Order::class);
        $repository->save($order);
        $event = new OrderConfirmedEvent($order->getId());
        $this->eventDispatcher->dispatch($event);
    }
}
