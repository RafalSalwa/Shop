<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class RedirectController
{
    #[Route('/redirect/{type}/{id}', name: 'redirect_item')]
    public function redirectToItem(string $type, int $id)
    {
        
    }
}