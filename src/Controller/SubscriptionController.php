<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\SubscriptionPlanService;
use App\Service\SubscriptionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[asController]
#[Route(path: '/subscriptions', name: 'subscriptions_', defaults: ['_format' => 'html'], methods: ['GET'])]
#[IsGranted('IS_AUTHENTICATED')]
final class SubscriptionController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function index(SubscriptionPlanService $planService): Response
    {
        $plans = $planService->fetchAvailablePlans();

        return $this->render(
            'subscriptions/index.html.twig',
            ['plans' => $plans],
        );
    }

    #[Route(path: '/order/{id}', name: 'order', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function order(
        int $id,
        SubscriptionPlanService $subscriptionPlanService,
        SubscriptionService $subscriptionService,
    ): Response {
        $plan = $subscriptionPlanService->findPlanById($id);
        if (null === $plan) {
            $this->addFlash('error', 'The subscription plan does not exist.');

            return $this->redirectToRoute('subscriptions_index');
        }
        $subscriptionService->assignSubscription($plan, $this->getUserId());
        $this->addFlash('success', ' subscription ordered successfully');

        return $this->redirectToRoute('subscriptions_index');
    }
}
