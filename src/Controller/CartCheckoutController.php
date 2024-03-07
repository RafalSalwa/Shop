<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\AddressBookService;
use App\Service\CartCalculatorService;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[asController]
#[Route(path: '/cart/checkout', name: 'checkout_', methods: ['GET', 'POST'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class CartCheckoutController extends AbstractShopController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        Request $request,
        CartService $cartService,
        CartCalculatorService $cartCalculator,
        AddressBookService $addressBookService,
    ): Response {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $address->setUserId($this->getUserId());
            $addressBookService->save($address);
        }

        return $this->render(
            'cart/checkout.html.twig',
            [
                'cart' => $cartService->getCurrentCart(),
                'summary' => $cartCalculator->calculateSummary(),
                'deliveryAddresses' => $addressBookService->getDeliveryAddresses($this->getUserId()),
                'form' => $form,
            ],
        );
    }

    #[Route(path: '/shipment', name: 'shipment', methods: ['GET', 'POST'])]
    public function shipment(
        Request $request,
        CartService $cartService,
        CartCalculatorService $cartCalculator,
        AddressBookService $addressService,
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

    #[Route(path: '/set_delivery_address', name: 'delivery_address_set', methods: ['POST'])]
    public function deliveryAddress(Request $request, CartService $cartService): Response
    {
        $deliveryAddressId = $request->request->get('addrId');
        $cartService->useDefaultDeliveryAddress($deliveryAddressId);

        return new Response('ok');
    }
}
