<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

use Closure;
use function count;

final class ClosureObject
{
    public function getItemProcessorStaticArrowFunction(): array
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item): int => $item * $multipier, $list);
    }

    public function getItemProcessorArrowFunction(): array
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item): int => $item * $multipier, $list);
    }

    public function getItemProcessorStatic(): Closure
    {
        $list = array_fill(0, 2000, 17);

        // Try with and without 'static' here
        return static function ($list): void {
            count($list);
        };
    }

    public function getItemProcessor(): array
    {
        $list = array_fill(0, 2000, 17);
        $multipier = 10;

        return array_map(static fn ($item): int => $item * $multipier, $list);
    }
}
