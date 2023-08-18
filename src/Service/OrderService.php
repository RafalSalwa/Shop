<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Workflow\WorkflowInterface;

class OrderService
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private WorkflowInterface $workflow;
    private SerializerInterface $serializer;
    private CartCalculator $cartCalculator;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security               $security,
        WorkflowInterface      $orderProcessing,
        SerializerInterface    $serializer,
        CartCalculator         $cartCalculator
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->workflow = $orderProcessing;
        $this->serializer = $serializer;
        $this->cartCalculator = $cartCalculator;
    }

    public function createPending(Cart $cart): Order
    {
        $order = new Order();
        $this->workflow->getMarking($order);
        $order->setAmount($this->cartCalculator->calculateTotal($cart));
        
        $user = $this->security->getUser();
        $repository = $this->entityManager->getRepository(Order::class);
        foreach ($cart->getItems() as $item) {
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
}
