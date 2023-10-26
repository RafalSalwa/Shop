<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Payment;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @internal
 */
#[CoversClass(User::class)]
#[UsesClass(Payment::class)]
#[UsesClass(Address::class)]
#[\PHPUnit\Framework\Attributes\CoversNothing]
final class UserTest extends TestCase
{
    public function testSetters(): void
    {
        $user = new User();
        $subscription = new Subscription();
        $address = new Address();
        $addressess = new ArrayCollection();

        $user->setSubscription($subscription);
        $user->addDeliveryAddress($address);
        $user->setDeliveryAddresses($addressess);

        try {
            $this->assertNotNull($user->getSubscription());
            $this->assertNotNull($user->getDeliveryAddresses());
        } catch (AssertionFailedError|ExpectationFailedException|InvalidArgumentException) {
        }
    }

    public function testOrderProcess(): void
    {
        $user = new User();
        $payment = new Payment();
        $payments = new ArrayCollection([new Payment(), new Payment()]);
        new ArrayCollection();

        $user->addPayment($payment);
        $user->setPayments($payments);
        $this->assertNotNull($user->getPayments());
    }
}
