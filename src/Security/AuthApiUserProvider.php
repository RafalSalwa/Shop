<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\User;
use App\Repository\SubscriptionRepository;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function func_get_args;

/** @template TUser of PasswordUpgraderInterface */
class AuthApiUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly HttpClientInterface $usersApi,
        private readonly SubscriptionRepository $subscriptionRepository,
        private readonly Security $security,
    ) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (null === $user->getSubscription()) {
            $subscription = $this->subscriptionRepository->findOneBy(['userId' => $user->getId()]);
            $user->setSubscription($subscription);
        }

        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $response = $this->usersApi->request('GET', '/user', [
                'auth_bearer' => $identifier,
            ]);
            dd(func_get_args(), $this->security->getUser());
            $user = new User();
            $user->setFromAuthApi($response);
            $user->getTokenPair();

            return $user;
        } catch (TransportExceptionInterface $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (Exception $ex) {
            dd($ex->getMessage(), $ex->getTraceAsString(), $response);
        }
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        dd(__FUNCTION__, __CLASS__);
        // TODO: Implement upgradePassword() method.
    }
}
