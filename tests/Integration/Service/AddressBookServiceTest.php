<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Repository\AddressRepository;
use App\Service\AddressBookService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: AddressBookService::class)]
#[UsesClass(className: AddressRepository::class)]
final class AddressBookServiceTest extends WebTestCase
{
    public function testGetDeliveryAddresses(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        $addressBookService = $container->get(AddressBookService::class);
        $newsletter = $addressBookService->getDeliveryAddresses(1);

        $this->assertCount(0, $newsletter);
    }

    public function testGetDefaultDeliveryAddress(): void
    {
        self::bootKernel();

        $container = self::getContainer();

        $addressBookService = $container->get(AddressBookService::class);
        $address = $addressBookService->getDefaultDeliveryAddress(1);

        $this->assertNull($address);
    }
}
