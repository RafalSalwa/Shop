<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use ReflectionClass;

trait ProtectedPropertyHelper
{
    /** @param int|string $value */
    private function setProtectedProperty(object $object, string $property, mixed $value): void
    {
        $reflectionClass = new ReflectionClass($object);
        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setValue($object, $value);
    }
}
