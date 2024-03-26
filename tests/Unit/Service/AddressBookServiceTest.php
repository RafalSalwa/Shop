<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Service\AddressBookService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: AddressBookService::class)]
#[UsesClass(className: AddressRepository::class)]
#[UsesClass(className: Address::class)]
final class AddressBookServiceTest extends TestCase
{
    public function testSaveAddress(): void
    {
        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Address $address): void {
                $this->assertSame(1, $address->getUserId());
                $this->assertSame('Test Street', $address->getAddressLine1());
                $this->assertSame('Test Street 2', $address->getAddressLine2());
                $this->assertSame('Test City', $address->getCity());
                $this->assertSame('Test State', $address->getState());
                $this->assertSame('11-222', $address->getPostalCode());
                $this->assertSame('Test Country', $address->getCountry());
                $this->assertTrue($address->isDefault());
            });

        $addressBookService = new AddressBookService($addressRepositoryMock);

        $address = new Address(1);
        $address->setId(1);
        $address->setUserId(1);
        $address->setAddressLine1('Test Street');
        $address->setAddressLine2('Test Street 2');
        $address->setCity('Test City');
        $address->setState('Test State');

        $address->setPostalCode('11-222');

        $address->setCountry('Test Country');
        $address->setDefault(true);

        $addressBookService->save($address);
    }

    public function testSetDefaultAddress(): void
    {
        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('setDefaultAddress')
            ->with(123, 1);

        $addressBookService = new AddressBookService($addressRepositoryMock);

        $addressBookService->setDefaultAddress(123, 1);
    }

    public function testGetDeliveryAddresses(): void
    {
        $userId = 1;
        $addresses = [
            new Address(1),
            new Address(2),
            new Address(3),
        ];

        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['userId' => $userId])
            ->willReturn($addresses);

        $addressBookService = new AddressBookService($addressRepositoryMock);

        $result = $addressBookService->getDeliveryAddresses($userId);

        $this->assertSame($addresses, $result);
    }

    public function testGetDefaultDeliveryAddress(): void
    {
        $userId = 1;
        $address = new Address(1);

        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('getDefaultForUser')
            ->with($userId)
            ->willReturn($address);

        $addressBookService = new AddressBookService($addressRepositoryMock);

        $result = $addressBookService->getDefaultDeliveryAddress($userId);

        $this->assertSame($address, $result);
    }
}
