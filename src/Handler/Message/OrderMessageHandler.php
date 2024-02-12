<?php

declare(strict_types=1);

namespace App\Handler\Message;

use App\Message\OrderMessage;

class OrderMessageHandler
{
    public function __invoke(OrderMessage $message): void
    {
        //        $orderId = $message->getOrderId();

        // Przetwarzanie zamówienia - np. zapis do bazy danych, generowanie faktury itp.
    }
}
