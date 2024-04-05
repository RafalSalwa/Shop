<?php

declare(strict_types=1);

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

use function assert;

final readonly class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator, private LoggerInterface $logger)
    {}

    public function handle(Request $request, AccessDeniedException $accessDeniedException): RedirectResponse
    {
        $this->logger->warning('Access denied', ['exception' => $accessDeniedException]);

        $session = $request->getSession();
        assert($session instanceof FlashBagAwareSessionInterface);
        $flashBag = $session->getFlashBag();

        $flashBag->add('error', 'You can only view pending orders that have been placed by You.');

        return new RedirectResponse($this->urlGenerator->generate('products_index'));
    }
}
