<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\SubscriptionPlan;
use App\Event\OrderConfirmedEvent;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    private CartService $cartService;

    public function __construct(
        EntityManagerInterface   $entityManager,
        Security                 $security,
        WorkflowInterface        $orderProcessing,
        SerializerInterface      $serializer,
        CartCalculator           $cartCalculator,
        EventDispatcherInterface $eventDispatcher,
        CartService              $cartService
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->workflow = $orderProcessing;
        $this->serializer = $serializer;
        $this->cartCalculator = $cartCalculator;
        $this->eventDispatcher = $eventDispatcher;
        $this->cartService = $cartService;
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
            $orderItem->setItemType($item->getType());
            $order->addItem($orderItem);
            $order->setUser($user);
        }
        $repository->save($order);
        return $order;
    }

    public function confirmOrder(Order $order)
    {
        $this->workflow->apply($order, "to_completed");

        /** @var OrderRepository $repository */
        $repository = $this->entityManager->getRepository(Order::class);
        $repository->save($order);

        $event = new OrderConfirmedEvent($order->getId());
        $this->eventDispatcher->dispatch($event);
    }

    public function assignDeliveryAddress(Order $order)
    {
        $repository = $this->entityManager->getRepository(Address::class);
        $addressId = $this->cartService->getDefaultDeliveryAddressId();
        $address = $repository->findOneBy(["id" => $addressId]);
        $order->setAddress($address);
    }

    public function deserializeOrderItems(Order $order)
    {
        $orderItems = new ArrayCollection();
        foreach ($order->getItems() as $key => $item) {
            $entityType = match ($item->getItemType()) {
                'plan' => SubscriptionPlan::class,
                'product' => Product::class
            };
            
            $deserialized = $this->serializer->deserialize($item->getCartItem(), $entityType, 'json');
            $orderItems->add($deserialized);
        }
        $order->setItems($orderItems);

    }
}
