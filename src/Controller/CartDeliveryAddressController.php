<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\AddressService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/delivery', name: 'delivery_', methods: ['POST'])]
final class CartDeliveryAddressController extends AbstractShopController
{
    #[Route(path: '/address/new', name: 'address_new')]
    public function add(Request $request, AddressService $service): Response
    {
        $user = $this->getShopUser();

        $address = new Address();
        $form    = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $service->save($address);

            return $this->redirectToRoute('checkout_shipment');
        }

        return $this->render(
            'shipping/address_new.html.twig',
            [
                'form'              => $form->createView(),
                'deliveryAddresses' => $user->getDeliveryAddresses(),
            ],
        );
    }
}
