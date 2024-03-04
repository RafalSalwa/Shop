<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\AddressService;
use App\Service\CartCalculatorService;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/cart/checkout', name: 'checkout_', methods: ['GET', 'POST'])]
final class CartCheckoutController extends AbstractShopController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render(
            'checkout/index.html.twig',
            [],
        );
    }

    #[Route(path: '/shipment', name: 'shipment', methods: ['GET', 'POST'])]
    public function shipment(
        Request $request,
        CartService $cartService,
        CartCalculatorService $cartCalculator,
        AddressService $addressService,
    ): Response {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $addressService->save($address);
        }

        return $this->render(
            'checkout/shipment.html.twig',
            [
                'form' => $form->createView(),
                'deliveryAddresses' => $user->getDeliveryAddresses(),
                'defaultAddress' => $cartService->getDefaultDeliveryAddressId(),
                'cart' => $cartService->getCurrentCart(),
                'payment' => $cartCalculator->calculatePayment($cartService->getCurrentCart()),
            ],
        );
    }
}
