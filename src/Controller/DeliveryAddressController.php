<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeliveryAddressController extends AbstractController
{
    #[Route('/cart/step/address/new', name: 'cart_shipment_address_new')]
    public function add(Request $request, AddressRepository $repository): Response
    {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $repository->save($address);
            return $this->redirectToRoute("checkout_shipment");
        }
        return $this->render('shipping/address_new.html.twig', [
            'form' => $form->createView(),
            "deliveryAddresses" => $user->getDeliveryAddresses()
        ]);
    }
}