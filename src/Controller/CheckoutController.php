<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Service\CartCalculator;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/cart/checkout/', name: 'checkout_index')]
    public function index(): Response
    {
        return $this->render(
            'checkout/index.html.twig',
            [],
        );
    }

    #[Route('/cart/checkout/shipment', name: 'checkout_shipment')]
    public function shipment(
        Request $request,
        CartService $cartService,
        CartCalculator $cartCalculator,
        AddressRepository $repository,
    ): Response {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $repository->save($address);
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
