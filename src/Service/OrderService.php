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
use MyNamespace\MyObject;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class OrderService
{
    private WorkflowInterface $workflow;

    public function __construct(
        WorkflowInterface $orderProcessing,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly SerializerInterface $serializer,
        private readonly CartCalculator $cartCalculator,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CartService $cartService,
        private readonly SubscriptionService $subscriptionService
    ) {
        $this->workflow = $orderProcessing;
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
//        $this->subscriptionService->assignSubscription();
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
        foreach ($order->getItems() as $item) {
            $entityType = match ($item->getItemType()) {
                'plan' => SubscriptionPlan::class,
                'product' => Product::class
            };

            $deserialized = $this->serializer->deserialize($item->getCartItem(), $entityType, 'json');
            $orderItems->add($deserialized);
        }
        $order->setItems($orderItems);
    }

    public function proceedSubscriptionsIfAny(Order $order)
    {
        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            if ($item->getItemType() == "plan") {
                $deserialized = json_decode($item->getCartItem(), true, 512, JSON_THROW_ON_ERROR);
                $this->subscriptionService->assignSubscription($deserialized["plan_name"]);
            }
        }
    }

}
