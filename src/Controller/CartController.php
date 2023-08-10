<?php

namespace App\Controller;

use App\Manager\CartManager;
use App\Repository\PlanRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/cart/add/{id}', name: 'cart_plan_add')]
    public function add(Request $request, int $id, PlanRepository $planRepository, CartManager $cartManager): Response
    {
        $cart = $cartManager->getCurrentCart();
        $plan = $planRepository->findById($id);
        if (!$plan) {
            $this->addFlash('info', 'Plan not found');
        }
        $cart
            ->addItem($plan)
            ->setUpdatedAt(new \DateTime());
        $cartManager->save($cart);
        dd($cart);

        return $this->redirectToRoute('plan_index', ['id' => $id]);
    }

    #[Route('/cart/', name: 'cart_index')]
    public function show(Request $request, CartManager $cartManager): Response
    {
        $cart = $cartManager->getCurrentCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }
}
