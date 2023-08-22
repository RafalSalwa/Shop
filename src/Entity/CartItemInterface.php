<?php

namespace App\Entity;

interface CartItemInterface
{

    public function getId(): int;

    public function getName(): string;

    public function getTypeName(): string;

    public function getDisplayName(): string;

}
