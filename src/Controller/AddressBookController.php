<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Service\AddressBookService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[asController]
#[Route(path: '/addressbook', name: 'addressbook_', methods: ['GET', 'POST'])]
#[IsGranted(attribute: 'ROLE_USER', statusCode: 403)]
final class AddressBookController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function index(AddressBookService $service): Response
    {
        return $this->render(
            'addressbook/index.html.twig',
            [
                'deliveryAddresses' => $service->getDeliveryAddresses($this->getShopUser()->getId()),
            ],
        );
    }

    #[Route(path: '/address/new', name: 'new')]
    public function add(Request $request, AddressBookService $service): Response
    {
        $user = $this->getShopUser();

        $address = new Address($this->getUserId());
        $form    = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $address->setUserId($this->getUserId());
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
