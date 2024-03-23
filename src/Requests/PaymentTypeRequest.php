<?php

declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class PaymentTypeRequest
{
    public function __construct(
        #[Assert\Type(type: 'string')]
        #[Assert\NotBlank()]
        private string $paymentType,
    ) {}

    public function getPaymentType(): string
    {
        return $this->paymentType;
    }
}
