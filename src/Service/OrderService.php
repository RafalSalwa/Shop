<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\CartItemInterface;
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
use function assert;
use function json_decode;
use const JSON_THROW_ON_ERROR;

class OrderService
{
    public function __construct(
        private readonly WorkflowInterface $orderProcessing,
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly SerializerInterface $serializer,
        private readonly CartCalculator $cartCalculator,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly CartService $cartService,
        private readonly SubscriptionService $subscriptionService,
    ) {}

    public function createPending(Cart $cart): Order
    {
        $order = new Order();
        $this->orderProcessing->getMarking($order);
        $order->setAmount($this->cartCalculator->calculateTotal($cart));

        $user = $this->security->getUser();

        $repository = $this->entityManager->getRepository(Order::class);
        assert($repository instanceof OrderRepository);

        foreach ($cart->getItems() as $item) {
            assert($item instanceof CartItemInterface);
            $entity = $item->getReferencedEntity();
            $serialized = $this->serializer->serialize($entity, 'json');

            $orderItem = new OrderItem();
            $orderItem->setCartItem($serialized);
            $orderItem->setItemType($item->getType());
            $order->addItem($orderItem);
            $order->setUser($user);
        }
        $repository->save($order);
        $this->assignDeliveryAddress($order);

        return $order;
    }

    public function assignDeliveryAddress(Order $order): void
    {
        $repository = $this->entityManager->getRepository(Address::class);
        $addressId = $this->cartService->getDefaultDeliveryAddressId();
        $address = $repository->findOneBy(
            ['id' => $addressId],
        );
        $order->setAddress($address);
    }

    public function confirmOrder(Order $order): void
    {
        $this->orderProcessing->apply($order, 'to_completed');

        $repository = $this->entityManager->getRepository(Order::class);
        assert($repository instanceof OrderRepository);
        $repository->save($order);

        $event = new OrderConfirmedEvent($order->getId());
        $this->eventDispatcher->dispatch($event);
    }

    public function deserializeOrderItems(Order $order): void
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

    public function proceedSubscriptionsIfAny(Order $order): void
    {
        foreach ($order->getItems() as $item) {
            assert($item instanceof OrderItem);
            if ('plan' !== $item->getItemType()) {
                continue;
            }

            $deserialized = json_decode($item->getCartItem(), true, 512, \JSON_THROW_ON_ERROR);
            $this->subscriptionService->assignSubscription($deserialized['plan_name']);
        }
    }
}
