<?php

namespace App\Controller;

use App\Service\CartService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/cart/order/pending/', name: 'cart_order_pending')]
    public function addToCart(?int $id = null, CartService $cartService, OrderService $orderService): Response
    {
        $cart = $cartService->getCurrentCart();
        $pending = $orderService->createPending($cart);
        
        return $this->render('order/pending.html.twig', [
            "pending" => $pending
        ]);
    }
}
