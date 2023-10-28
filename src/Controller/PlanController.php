<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SubscriptionPlan;
use App\Repository\PlanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{
    #[Route('/plan', name: 'plan_index')]
    public function index(PlanRepository $planRepository): Response
    {
        $plans = $planRepository->fetchAvailablePlans();

        return $this->render(
            'plan/index.html.twig',
            [
                'controller_name' => 'IndexController',
                'plans'           => $plans,
            ],
        );
    }

    #[Route('/plan/{id<\d+>}', name: 'plan_details', methods: ['GET'])]
    public function details(SubscriptionPlan $plan): Response
    {
        return $this->render(
            'plan/details.html.twig',
            [
                'controller_name' => 'IndexController',
                'plan'            => $plan,
            ],
        );
    }
}
