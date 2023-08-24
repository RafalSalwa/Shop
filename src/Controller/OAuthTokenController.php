<?php

namespace App\Controller;

use App\Entity\OAuth2UserConsent;
use App\Repository\ProductRepository;
use App\Service\OAuth2Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OAuthTokenController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/callback', name: 'oauth_callback')]
    public function callback(Request $request, Session $session, HttpClientInterface $client): Response
    {
        if ($request->query->has("code")) {
            $session->set("oauth2_code", $request->get("code"));
        }
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => 'testclient',
            'client_secret' => 'testpass',
            'redirect_uri' => 'http://localhost:8080/callback',
            'code' => urldecode($request->get("code")),
        ];

        return $this->render('oauth_token/callback.html.twig', [
            'parameters' => $params,
            'params' => http_build_query($params)
        ]);
    }

    #[Route('/oauth/token', name: 'oauth_token_index')]
    public function index(Request $request, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        return $this->render('oauth_token/index.html.twig', [
        ]);
    }

    #[Route('/consent', name: 'app_consent', methods: ['GET', 'POST'])]
    public function consent(Request $request, OAuth2Service $service): Response
    {
        $appClient = $service->getClient();
        $user = $this->getUser();
        $userConsents = $user->getOAuth2UserConsents()->filter(
            fn(OAuth2UserConsent $consent) => $consent->getClient() === $appClient
        )->first() ?: null;
        $userScopes = $userConsents?->getScopes() ?? [];
        $hasExistingScopes = count($userScopes) > 0;
        $requestedScopes = explode(' ', $request->query->get('scope'));
        // If user has already consented to the scopes, give consent
        if (count(array_diff($requestedScopes, $userScopes)) === 0) {
            $request->getSession()->set('consent_granted', true);
            return $this->redirectToRoute('oauth2_authorize', $request->query->all());
        }

        if ($request->isMethod('POST')) {
            $consents = $service->createConsent($appClient);
            $user->addOAuth2UserConsent($consents);
            $service->save($consents);
            return $this->redirectToRoute('oauth2_authorize', $request->query->all(), 307);
        }
        $this->redirectToRoute("oauth_token_index");
    }
}
