<?php

namespace App\Handler\Message;

use App\Message\UserLoggedIn;

class UserLoggedInHandler
{
    public function __invoke(UserLoggedIn $message): void
    {
        $userId = $message->getContent();
        // Przetwarzanie zamówienia - np. zapis do bazy danych, generowanie faktury itp.
    }
}