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
#[Route(path: '/cart/shipment', name: 'shipment_', methods: ['GET', 'POST'])]
final class CartShipmentController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function shipment(
        Request $request,
        CartService $cartService,
        CartCalculatorService $cartCalculator,
        AddressService $service,
    ): Response {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $service->save($address);
        }

        return $this->render(
            'shipping/step_one.html.twig',
            [
                'form' => $form->createView(),
                'deliveryAddresses' => $user->getDeliveryAddresses(),
                'cart' => $cartService->getCurrentCart(),
                'payment' => $cartCalculator->calculatePayment($cartService->getCurrentCart()),
            ],
        );
    }
}
