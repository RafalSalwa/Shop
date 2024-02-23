<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/delivery', name: 'delivery_', methods: ['POST'])]
final class CartDeliveryAddressController extends AbstractController
{
    #[Route(path: '/address/new', name: 'address_new')]
    public function add(Request $request, AddressRepository $repository): Response
    {
        $user = $this->getUser();

        $address = new Address();
        $form    = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $repository->save($address);

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
