<?php

declare(strict_types=1);

namespace App\Messenger\Handler;

use App\Messenger\Message\UserLoggedIn;

final class UserLoggedInHandler
{
    public function __invoke(UserLoggedIn $userLoggedIn): void
    {
        //        $message->getContent();
        // Przetwarzanie zamówienia - np. zapis do bazy danych, generowanie faktury itp.
    }
}
