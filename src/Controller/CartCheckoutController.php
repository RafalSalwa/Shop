<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Service\AddressBookService;
use App\Service\CartCalculatorService;
use App\Service\CartService;
use App\Service\PaymentService;
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
        $deliveryAddressId = $request->request->get('addrId');
        $addressBookService->setDefaultAddress($deliveryAddressId, $this->getUserId());

        return $this->render(
            'cart/partials/default_address.html.twig',
            [
                'address' => $addressBookService->getDefaultDeliveryAddress($this->getUserId()),
            ],
        );
    }

    #[Route(path: '/payment_method', name: 'payment_method', methods: ['PUT'])]
    public function paymentMethod(Request $request, PaymentService $paymentService): Response
    {
        $paymentMethod = $request->request->get('payment_method');
        $paymentService->setDefaultAddress($deliveryAddressId, $this->getUserId());

        return $this->render(
            'cart/partials/default_address.html.twig',
            [
                'address' => $addressBookService->getDefaultDeliveryAddress($this->getUserId()),
            ],
        );
    }
}
