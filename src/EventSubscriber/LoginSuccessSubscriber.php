<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Message\AMQPMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private const EVENT_NAME = 'customer_logged_in';

    public static function getSubscribedEvents()
    {
        return [
            LoginSuccessEvent::class => 'onSuccessfulLogin',
        ];
    }

    public function onSuccessfulLogin(LoginSuccessEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();

        $message = new AMQPMessage();
        $message->setId($user->getId());
        $message->setName(self::EVENT_NAME);

        //        $this->messageBus->dispatch(
        //            $message
        //            ,
        //            [
        //                new SerializerStamp(['json_encode_options' => JSON_UNESCAPED_UNICODE]),
        //                new AmqpStamp(null, AMQP_NOPARAM, [
        //                    'content_type' => 'application/json',
        //                    'content_encoding' => 'UTF-8',
        //                ]),
        //            ],
        //        );
    }
}
