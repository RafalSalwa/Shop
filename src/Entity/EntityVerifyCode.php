<?php

declare(strict_types=1);

namespace App\Entity;

class EntityVerifyCode
{
    protected string $code = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
