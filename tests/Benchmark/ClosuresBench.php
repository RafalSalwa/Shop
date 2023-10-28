<?php

namespace App\Tests\Benchmark;

class ClosuresBench
{

    public function benchLargeObjectStatic()
    {
        $processors = [];
        for ($i = 0; $i < 2000; $i++) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorStatic();
        }
    }

    public function benchLargeObject()
    {
        $processors = [];
        for ($i = 0; $i < 2000; $i++) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessor();
        }
    }

    public function benchLargeObjectArrowFunctionStatic()
    {
        $processors = [];
        for ($i = 0; $i < 2000; $i++) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorArrowStatic();
        }
    }

    public function benchLargeObjectArrowFunction()
    {
        $processors = [];
        for ($i = 0; $i < 2000; $i++) {
            $lo = new LargeObject();
            $processors[] = $lo->getItemProcessorArrow();
        }
    }

    public function benchArrowFunction()
    {
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorStaticArrowFunction();
    }

    public function benchStaticArrowFunction()
    {
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorArrowFunction();
    }

    public function benchClosures()
    {
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessor();
    }

    public function benchStaticClosures()
    {
        $list = array_fill(0, 2000, 17);
        $lo = new ClosureObject();
        $processors[] = $lo->getItemProcessorStatic();
    }

}