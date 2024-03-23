<?php

declare(strict_types=1);

namespace App\Messenger\Message;

use JsonSerializable;

final class AMQPMessage implements JsonSerializable
{
    private string $name = '';

    private int $id = 0;

    private string $sequenceId = '';

    private string $timestamp = '';

    private string $content = '';

    private string $persist = '';

    private string $channel = '';

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /** @return array<string, string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'seq' => $this->getSequenceId(),
            'ts' => $this->getTimestamp(),
            'content' => $this->getContent(),
            'store' => $this->getPersist(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSequenceId(): string
    {
        return $this->sequenceId;
    }

    public function setSequenceId(string $sequenceId): void
    {
        $this->sequenceId = $sequenceId;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPersist(): string
    {
        return $this->persist;
    }

    public function setPersist(string $persist): void
    {
        $this->persist = $persist;
    }
}
