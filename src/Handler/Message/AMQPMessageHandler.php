<?php

declare(strict_types=1);

namespace App\Handler\Message;

use App\Message\AMQPMessage;

class AMQPMessageHandler
{
    public function __invoke(AMQPMessage $message): void
    {
        $message->getContent();
    }
}
