<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/payment', name: 'payment_')]
final class PaymentController extends AbstractShopController
{
}
