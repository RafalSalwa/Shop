<?php

namespace App\Controller;

use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SubscriptionController extends AbstractController
{

    #[Route('/subscription/cancel', name: 'subscription_cancel', defaults: ['_format' => 'html'], methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(SubscriptionService $service): Response
    {
        $service->cancelSubscription();
        $this->addFlash("info", "Subscription removed");
        return $this->redirectToRoute("app_index");
    }


}
