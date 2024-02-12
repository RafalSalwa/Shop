<?php

declare(strict_types=1);

namespace App\Message;

use JsonSerializable;

class AMQPMessage implements JsonSerializable
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSequenceId(): string
    {
        return $this->sequenceId;
    }

    public function setSequenceId(string $sequenceId): self
    {
        $this->sequenceId = $sequenceId;

        return $this;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPersist(): string
    {
        return $this->persist;
    }

    public function setPersist(string $persist): self
    {
        $this->persist = $persist;

        return $this;
    }
}
