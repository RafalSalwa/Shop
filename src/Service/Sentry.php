<?php

declare(strict_types=1);

namespace App\Service;

use Sentry\Breadcrumb;
use Sentry\Event;
use Sentry\EventHint;
use Sentry\Tracing\SamplingContext;

class Sentry
{
    public function getTracesSampler(): callable
    {
        return static fn (SamplingContext $context): float => 1;
    }

    public function getBeforeBreadcrumb(): callable
    {
        return static fn (Breadcrumb $breadcrumb): ?Breadcrumb => $breadcrumb;
    }

    public function getBeforeSend(): callable
    {
        return static fn (Event $event, ?EventHint $hint): ?Event => $event;
    }
}
