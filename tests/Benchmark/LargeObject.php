<?php

namespace App\Tests\Benchmark;

use Closure;

class LargeObject
{
    protected $array;

    public function __construct()
    {
        $this->array = array_fill(0, 2000, 17);
    }

    public function getItemProcessorStatic(): Closure
    {
        return static function () {
        };
    }

    public function getItemProcessor(): Closure
    {
        return function () {
        };
    }

    public function getItemProcessorArrowStatic(): Closure
    {
        return static fn() => "";
    }

    public function getItemProcessorArrow(): Closure
    {
        return fn() => "";
    }
}
