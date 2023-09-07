<?php

namespace App\Handler\Message;

use App\Message\AMQPMessage;

class AMQPMessageHandler
{
    public function __invoke(AMQPMessage $message)
    {
        $userId = $message->getContent();
    }
}