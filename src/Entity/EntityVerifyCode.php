<?php

namespace App\Entity;

class EntityVerifyCode
{
    protected string $code = '';

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): EntityVerifyCode
    {
        $this->code = $code;
        return $this;
    }
}
