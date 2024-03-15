<?php

namespace App\ValueObject\GRPC;

use stdClass;
use const Grpc\STATUS_OK;

final class StatusResponse {

    private array $metadata;
    private int $code;
    private string $details;
    public function __construct(stdClass $class)
    {
        $this->metadata = $class->metadata;
        $this->code = $class->code;
        $this->details = $class->details;
    }

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
        return $this->code === STATUS_OK;
    }
}
