<?php

declare(strict_types=1);

namespace App\Enum;

enum PaymentProvider: string
{
    case Stripe = 'stripe';
    case Paypal = 'paypal';
    case CreditCard = 'credit-card';
    case PaymentOnDelivery = 'payment-on-delivery';

    public static function defaultValue(): string
    {
        return self::Stripe->value;
    }
}
