<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

use Closure;

class LargeObject
{
    protected array $array;

    public function __construct()
    {
        $this->array = array_fill(0, 2000, 17);
    }

    public function getItemProcessorStatic(): Closure
    {
        return static function (): void {};
    }

    public function getItemProcessor(): Closure
    {
        return static function (): void {};
    }

    public function getItemProcessorArrowStatic(): Closure
    {
        return static fn (): string => '';
    }

    public function getItemProcessorArrow(): Closure
    {
        return static fn (): string => '';
    }
}
