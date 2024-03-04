<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\AMQPMessage;

final class AMQPMessageHandler
{
    public function __invoke(AMQPMessage $amqpMessage): void
    {
        $amqpMessage->getContent();
    }
}
