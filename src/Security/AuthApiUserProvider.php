<?php
declare(strict_types=1);
namespace App\Security;

use App\Model\ApiTokenPair;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthApiUserProvider {

    public function __construct(
        private readonly HttpClientInterface $authApi,
    ){}

    public function getTokens(string $email, string $password): ApiTokenPair
    {
        try{
            $response = $this->authApi->request('POST','/auth/signin',[
                'body'=>json_encode(['username'=>'rafal@interview.com', 'password'=>'VeryG00dPass!'])
            ]);
            $arrResponse = json_decode($response->getContent(),true);
            $apiToken = new ApiTokenPair($arrResponse['user']['token'], $arrResponse['user']['refresh_token']);
            return $apiToken;
        } catch(ClientException $ce){
            dd($ce->getMessage(), $ce->getTraceAsString());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }
}
