<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Service\AddressBookService;
use App\Service\OrderService;
use App\Service\SubscriptionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[Route(path: '/user', name: 'user_', methods: ['GET'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class ProfileController extends AbstractShopController
{
    #[Route(path: '/orders/{page}', name: 'orders', defaults: ['page' => '1'])]
    public function index(int $page, OrderService $orderService): Response
    {
        return $this->render(
            'user/profile.html.twig',
            [
                'pendings' => $orderService->fetchOrders(
                    userId: $this->getUserId(),
                    page: $page,
                    status: [Order::PENDING],
                ),
                'orders' => $orderService->fetchOrders($this->getUserId()),
            ],
        );
    }

    #[Route(path: '/addressbook', name: 'addressbook')]
    public function addressbook(AddressBookService $addressBookService): Response
    {
        return $this->render(
            'user/addressbook.html.twig',
            [
                'addresses' => $addressBookService->getDeliveryAddresses($this->getUserId()),
                'defaultAddress' => $addressBookService->getDefaultDeliveryAddress($this->getUserId()),
            ],
        );
    }

    #[Route(path: '/subscriptions', name: 'subscriptions')]
    public function clear(SubscriptionService $subscriptionService): Response
    {
        $subscriptionService->cancelSubscription($this->getUserId());
        $this->addFlash('info', 'Subscription removed');

        return $this->redirectToRoute('app_index');
    }
}
