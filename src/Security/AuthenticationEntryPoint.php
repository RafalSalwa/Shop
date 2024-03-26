<?php

declare(strict_types=1);

namespace App\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

use function assert;

final readonly class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private LoggerInterface $logger,
    ) {}

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $session = $request->getSession();
        assert($session instanceof FlashBagAwareSessionInterface);
        $flashBag = $session->getFlashBag();

        if (null !== $authException) {
            $this->logger->info($authException->getMessage());
            $flashBag->add('info', 'You have to login in order to access this page.');
        }

        return new RedirectResponse($this->urlGenerator->generate('login_index'));
    }
}
