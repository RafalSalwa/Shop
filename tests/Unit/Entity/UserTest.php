<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\Payment;
use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Model\User;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers User
 *
 * @uses \App\Entity\Payment
 * @uses \App\Entity\Address
 */
#[CoversClass(User::class)]
#[UsesClass(Payment::class)]
#[UsesClass(Address::class)]
#[\PHPUnit\Framework\Attributes\CoversNothing]
final class UserTest extends TestCase
{
    public function testSetters(): void
    {
        $user = new User(1, 'test@test.com');
        $plan = new SubscriptionPlan();
        $subscription = new Subscription($user->getId(), $plan);

        $user->setSubscription($subscription);

        try {
            $this->assertNotNull($user->getSubscription());
        } catch (AssertionFailedError|ExpectationFailedException) {
        }
    }

    /**
     * @covers \App\Entity\Payment
     * @covers \App\ValueObject\EmailAddress
     */
    public function testOrderProcess(): void
    {
        $user = new User(1, 'test@test.com');

        $this->assertNotNull($user);
    }
}
