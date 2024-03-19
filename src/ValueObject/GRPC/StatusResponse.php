<?php

declare(strict_types=1);

namespace App\ValueObject\GRPC;

use stdClass;
use const Grpc\STATUS_OK;

/**
 * Converts GRPC response array into more readable form
 */
final class StatusResponse
{
    private int $code;

    public function __construct(stdClass $class)
    {
        $this->code = $class->code;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isOk(): bool
    {
        return STATUS_OK === $this->getCode();
    }
}
