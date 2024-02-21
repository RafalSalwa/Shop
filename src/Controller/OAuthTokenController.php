<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OAuth2UserConsent;
use App\Service\OAuth2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use function array_diff;
use function explode;
use function http_build_query;
use function urldecode;

class OAuthTokenController extends AbstractController
{
    #[Route('/callback', name: 'oauth_callback')]
    public function callback(Request $request, Session $session): Response
    {
        if (true === $request->query->has('code')) {
            $session->set('oauth2_code', $request->get('code'));
        }

        $params = [
            'grant_type'    => 'authorization_code',
            'client_id'     => 'testclient',
            'client_secret' => 'testpass',
            'redirect_uri'  => 'http://localhost:8080/callback',
            'code'          => urldecode((string)$request->get('code')),
        ];

        return $this->render(
            'oauth_token/callback.html.twig',
            [
                'parameters' => $params,
                'params'     => http_build_query($params),
            ],
        );
    }

    #[Route('/oauth/token', name: 'oauth_token_index')]
    public function index(): Response
    {
        return $this->render('oauth_token/index.html.twig', []);
    }

    #[Route('/consent', name: 'app_consent', methods: ['GET', 'POST'])]
    public function consent(Request $request, OAuth2Service $oAuth2Service): Response
    {
        $appClient    = $oAuth2Service->getClient();
        $user         = $this->getUser();
        $userConsents = $user->getOAuth2UserConsents()
            ->filter(static fn (OAuth2UserConsent $oAuth2UserConsent): bool => $oAuth2UserConsent->getClient() === $appClient)
            ->first()
        ;
        $userScopes      = $userConsents->getScopes();
        $requestedScopes = explode(' ', $request->query->get('scope'));
        if ([] === array_diff($requestedScopes, $userScopes)) {
            $request->getSession()
                ->set('consent_granted', true)
            ;

            return $this->redirectToRoute('oauth2_authorize', $request->query->all());
        }

        if ($request->isMethod('POST')) {
            $consents = $oAuth2Service->createConsent($appClient);
            $user->addOAuth2UserConsent($consents);
            $oAuth2Service->save($consents);

            return $this->redirectToRoute('oauth2_authorize', $request->query->all(), 307);
        }

        return $this->redirectToRoute('oauth_token_index');
    }
}
