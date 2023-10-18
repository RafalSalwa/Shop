<?php

namespace App\Handler\Message;

use App\Message\OrderMessage;

class OrderMessageHandler
{
    public function __invoke(OrderMessage $message)
    {
        //        $orderId = $message->getOrderId();

        // Przetwarzanie zamówienia - np. zapis do bazy danych, generowanie faktury itp.
    }
}
