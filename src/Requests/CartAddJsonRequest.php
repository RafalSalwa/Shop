<?php

declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CartAddJsonRequest
{
    public function __construct(
        #[Assert\Type('integer')]
        #[Assert\NotBlank()]
        public int $id,
        #[Assert\NotBlank([])]
        public string $type,
        #[Assert\Type('integer')]
        #[Assert\NotBlank()]
        public int $quantity,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
