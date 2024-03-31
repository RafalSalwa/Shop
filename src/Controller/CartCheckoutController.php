<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Service\AddressBookService;
use App\Service\CalculatorService;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
#[Route(path: '/cart/checkout', name: 'checkout_', methods: ['GET', 'POST'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class CartCheckoutController extends AbstractShopController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        Request $request,
        CartService $cartService,
        CalculatorService $cartCalculator,
        AddressBookService $addressBookService,
    ): Response {
        $address = new Address($this->getUserId());
        $form = $this->createForm(AddressType::class, $address);
        $paymentForm = $this->createForm(PaymentType::class);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $address->setUserId($this->getUserId());
            $addressBookService->save($address);
        }

        $cart = $cartService->getCurrentCart();

        return $this->render(
            'cart/checkout.html.twig',
            [
                'cart' => $cart,
                'summary' => $cartCalculator->calculateSummary($cart->getTotalAmount(), $cart->getCoupon()),
                'deliveryAddresses' => $addressBookService->getDeliveryAddresses($this->getUserId()),
                'defaultAddress' => $addressBookService->getDefaultDeliveryAddress($this->getUserId()),
                'form' => $form,
                'paymentForm' => $paymentForm,
            ],
        );
    }

    #[Route(path: '/set_delivery_address', name: 'delivery_address_set', methods: ['PUT'])]
    public function deliveryAddress(Request $request, AddressBookService $addressBookService): Response
    {
        $deliveryAddressId = $request->request->getInt('addrId');
        $addressBookService->setDefaultAddress($deliveryAddressId, $this->getUserId());

        return $this->render(
            'cart/partials/default_address.html.twig',
            [
                'address' => $addressBookService->getDefaultDeliveryAddress($this->getUserId()),
            ],
        );
    }
}
