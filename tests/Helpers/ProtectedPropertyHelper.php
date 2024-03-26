<?php

namespace App\Tests\Helpers;

use ReflectionClass;

trait ProtectedPropertyHelper {

    private function setProtectedProperty($object, $property, $value): void
    {
        $reflectionClass = new ReflectionClass($object);
        $reflection_property = $reflectionClass->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }

}
