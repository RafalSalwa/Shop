<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

use Closure;
use function count;

class ClosureObject
{
    public function getItemProcessorStaticArrowFunction()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item) => $item * $multipier, $list);
    }

    public function getItemProcessorArrowFunction()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item) => $item * $multipier, $list);
    }

    public function getItemProcessorStatic(): Closure
    {
        $list = array_fill(0, 2000, 17);

        // Try with and without 'static' here
        return static function ($list): void {
            count($list);
        };
    }

    public function getItemProcessor()
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item) => $item * $multipier, $list);
    }
}
