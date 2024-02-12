<?php

declare(strict_types=1);

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private readonly HttpClientInterface $authApi,
    ){}

    protected function getLoginUrl(Request $request): string
    {
        return '/login';
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function authenticate(Request $request): Passport
    {
        try{
            $response = $this->authApi->request('POST','/auth/signin',[
                'body'=>json_encode(
                    [
                        'username'=>$request->get('email'),
                        'password'=>$request->get('password')
                    ])
            ]);
            $arrResponse = json_decode($response->getContent(),true);
        dd($arrResponse);
        dd($response->getContent(), $response->getHeaders(), $response->getStatusCode());
        } catch(ClientException $ce){
            dd($ce->getMessage(), $ce->getTraceAsString());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }

        return new Passport(
            new UserBadge($request->request->get('email')),
            new CustomCredentials(function(string $credentials, UserInterface $user):bool {

            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

}
