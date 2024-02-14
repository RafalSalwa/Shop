<?php

declare(strict_types=1);

namespace App\Tests\Benchmark;

class ClosuresBench
{
    public function benchLargeObjectStatic(): void
    {
        $processors = [];
        for ($i = 0; $i < 2000; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorStatic();
        }
    }

    public function benchLargeObject(): void
    {
        $processors = [];
        for ($i = 0; $i < 2000; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessor();
        }
    }

    public function benchLargeObjectArrowFunctionStatic(): void
    {
        $processors = [];
        for ($i = 0; $i < 2000; ++$i) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorArrowStatic();
        }
    }

    public function benchLargeObjectArrowFunction(): void
    {
        $processors = [];
        for ($i = 0; $i < 2000; ++$i) {
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
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorArrowFunction();
    }

    public function benchClosures(): void
    {
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessor();
    }

    public function benchStaticClosures(): void
    {
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorStatic();
    }
}
