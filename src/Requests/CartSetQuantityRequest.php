<?php

declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CartSetQuantityRequest
{
    public function __construct(
        #[Assert\Type('integer')]
        #[Assert\NotBlank()]
        public int $id,
        #[Assert\Type('integer')]
        #[Assert\NotBlank()]
        public int $quantity,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
