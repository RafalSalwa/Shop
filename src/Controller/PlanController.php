<?php

namespace App\Controller;

use App\Entity\SubscriptionPlan;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{

    #[Route('/plan/{id}', name: 'plan_index')]
    public function index(Request $request, SubscriptionPlan $plan): Response
    {
        return $this->render('plan/index.html.twig', [
            'controller_name' => 'IndexController',
            'plan' => $plan
        ]);
    }
}