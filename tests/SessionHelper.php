<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

trait SessionHelper
{
    public function generateCsrfToken(KernelBrowser $kernelBrowser, string $tokenId): string
    {
        $session = $this->getSession($kernelBrowser);
        $container = static::getContainer();
        $tokenGenerator = $container->get('security.csrf.token_generator');
        $csrfToken = $tokenGenerator->generateToken();
        $session->set(SessionTokenStorage::SESSION_NAMESPACE . ('/' . $tokenId), $csrfToken);
        $session->save();

        return $csrfToken;
    }

    public function getSession(KernelBrowser $kernelBrowser): Session
    {
        $cookie = $kernelBrowser->getCookieJar()
            ->get('MOCKSESSID')
        ;

        // create a new session object
        $container = static::getContainer();
        $session = $container->get('session.factory')
            ->createSession()
        ;
        if ($cookie instanceof Cookie) {
            // get the session id from the session cookie if it exists
            $session->setId($cookie->getValue());
            $session->start();
        } else {
            // or create a new session id and a session cookie
            $session->start();
            $session->save();

            $sessionCookie = new Cookie($session->getName(), $session->getId(), null, null, 'localhost');
            $kernelBrowser->getCookieJar()
                ->set($sessionCookie)
            ;
        }

        return $session;
    }

    public function createSession(KernelBrowser $kernelBrowser): Session
    {
        $container = $kernelBrowser->getContainer();
        $sessionSavePath = $container->getParameter('session.save_path');
        $mockFileSessionStorage = new MockFileSessionStorage($sessionSavePath);

        $session = new Session($mockFileSessionStorage);
        $session->start();
        $session->save();

        $sessionCookie = new Cookie($session->getName(), $session->getId(), null, null, 'localhost');
        $kernelBrowser->getCookieJar()
            ->set($sessionCookie)
        ;

        return $session;
    }
}
