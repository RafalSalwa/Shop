<?php

namespace App\Tests\Benchmark;

use Closure;

class ClosureObject
{
    public function getItemProcessorStaticArrowFunction()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn($item) => $item * $multipier, $list);
    }

    public function getItemProcessorArrowFunction()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(fn($item) => $item * $multipier, $list);
    }

    public function getItemProcessorStatic(): Closure
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        // Try with and without 'static' here
        return static function ($list) {
            count($list);
        };
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static function ($item) use ($list, $multipier) {
            return $item * $multipier;
        }, $list);
    }

    public function getItemProcessor()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(function ($item) use ($list, $multipier) {
            return $item * $multipier;
        }, $list);
    }
}