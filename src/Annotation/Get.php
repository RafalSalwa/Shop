<?php

declare(strict_types=1);

namespace App\Annotation;

use Symfony\Component\Routing\Annotation\Route;

#[\Attribute]
class Get extends Route
{
    public function getMethods(): array
    {
        return ['GET'];
    }
}
