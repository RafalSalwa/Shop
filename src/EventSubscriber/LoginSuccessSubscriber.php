<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

final readonly class LoginSuccessSubscriber implements EventSubscriberInterface
{

    private const EVENT_NAME = "customer_logged_in";

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @inheritDoc
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [LoginSuccessEvent::class => 'onSuccessfulLogin'];
    }

    public function onSuccessfulLogin(LoginSuccessEvent $loginSuccessEvent): void
    {
        /** @var User $user */
        $user = $loginSuccessEvent->getUser();

        $amqpMessage = new AMQPMessage();
        $amqpMessage->setId($user->getId());
        $amqpMessage->setName(self::EVENT_NAME);

        $this->messageBus->dispatch(
            $amqpMessage
            ,
            [
                new SerializerStamp(['json_encode_options' => JSON_UNESCAPED_UNICODE]),
                new AmqpStamp(null, AMQP_NOPARAM, [
                    'content_type' => 'application/json',
                    'content_encoding' => 'UTF-8',
                ]),
            ],
        );
    }
}
