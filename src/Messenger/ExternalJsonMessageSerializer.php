<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Message\AMQPMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
    }

    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        if (! $message instanceof AMQPMessage) {
            throw new \Exception('Unsupported message class');
        }
        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        return [
            'body' => json_encode($message, \JSON_THROW_ON_ERROR),
            'headers' => [
                'stamps' => serialize($allStamps),
            ],
        ];
    }
}
