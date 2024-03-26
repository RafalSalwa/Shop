<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

final class ClosuresBench
{
    private const TRIES = 2000;

    public function benchLargeObjectStatic(): void
    {
        $processors = [];
        for ($i = 0; $i < self::TRIES; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorStatic();
        }
    }

    public function benchLargeObject(): void
    {
        $processors = [];
        for ($i = 0; $i < self::TRIES; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessor();
        }
    }

    public function benchLargeObjectArrowFunctionStatic(): void
    {
        $processors = [];
        for ($i = 0; $i < self::TRIES; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorArrowStatic();
        }
    }

    public function benchLargeObjectArrowFunction(): void
    {
        $processors = [];
        for ($i = 0; $i < self::TRIES; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorArrow();
        }
    }

    public function benchArrowFunction(): void
    {
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorStaticArrowFunction();
    }

    public function benchStaticArrowFunction(): void
    {
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorArrowFunction();
    }

    public function benchClosures(): void
    {
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessor();
    }

    public function benchStaticClosures(): void
    {
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorStatic();
    }
}
