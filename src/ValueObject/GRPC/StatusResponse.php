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
    /** @var array<string, string> $metadata */
    private array $metadata;

    private int $code;

    private string $details;

    public function __construct(stdClass $class)
    {
        $this->metadata = $class->metadata;
        $this->code = $class->code;
        $this->details = $class->details;
    }

    /** @return array<string, string> */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function isOk(): bool
    {
        return STATUS_OK === $this->code;
    }
}
