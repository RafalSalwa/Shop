<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    #[Route('/cart/step/shipment', name: 'cart_shipment')]
    public function shipment(Request $request, CartService $cartService, AddressRepository $repository): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $address->setUser($this->getUser());
            $repository->save($address);
        }
        $user = $this->getUser();
        $adressess = $user->getAddressess();
        return $this->render('shipping/step_one.html.twig', [
            'form' => $form->createView(),
            "addrs" => $adressess
        ]);
    }
}