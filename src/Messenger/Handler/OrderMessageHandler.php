<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\OrderMessage;

final class OrderMessageHandler
{
    public function __invoke(OrderMessage $orderMessage): void
    {
        //        $orderId = $message->getOrderId();

        // Przetwarzanie zamówienia - np. zapis do bazy danych, generowanie faktury itp.
    }
}
