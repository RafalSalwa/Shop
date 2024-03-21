<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\AMQPMessage;
use function dd;

final class AMQPMessageHandler
{
    public function __invoke(AMQPMessage $amqpMessage): void
    {
        dd($amqpMessage->getContent());
    }
}
