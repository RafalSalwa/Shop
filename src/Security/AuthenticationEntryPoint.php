<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

use function assert;

final class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
    // phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        $flashBag = $request->getSession()->getFlashBag();
        assert($flashBag instanceof FlashBagInterface);
        $flashBag->add('info', 'You have to login in order to access this page.');

        return new RedirectResponse($this->urlGenerator->generate('login_index'));
    }
}
