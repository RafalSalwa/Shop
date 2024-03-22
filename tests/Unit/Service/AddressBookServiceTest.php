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
            ->willReturnCallback(function (Address $address) {
                $this->assertEquals(1, $address->getUserId());
                $this->assertEquals('Test Street', $address->getAddressLine1());
                $this->assertEquals('Test Street 2', $address->getAddressLine2());
                $this->assertEquals('Test City', $address->getCity());
                $this->assertEquals('Test State', $address->getState());
                $this->assertEquals('Test Zip', $address->getPostalCode());
                $this->assertEquals('Test Country', $address->getCountry());
                $this->assertTrue($address->isDefault());
            });

        $addressService = new AddressBookService($addressRepositoryMock);

        $address = new Address();
        $address->setId(1);
        $address->setUserId(1);
        $address->setAddressLine1('Test Street');
        $address->setAddressLine2('Test Street 2');
        $address->setCity('Test City');
        $address->setState('Test State');
        $address->setPostalCode('Test Zip');
        $address->setCountry('Test Country');
        $address->setDefault(true);
        $addressService->save($address);
    }

    public function testSetDefaultAddress(): void
    {
        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('setDefaultAddress')
            ->with(123, 1);

        $addressService = new AddressBookService($addressRepositoryMock);

        $addressService->setDefaultAddress(123, 1);
    }

    public function testGetDeliveryAddresses(): void
    {
        $userId = 1;
        $addresses = [
            new Address(),
            new Address(),
            new Address(),
        ];

        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('findBy')
            ->with(['userId' => $userId])
            ->willReturn($addresses);

        $addressService = new AddressBookService($addressRepositoryMock);

        $result = $addressService->getDeliveryAddresses($userId);

        $this->assertEquals($addresses, $result);
    }

    public function testGetDefaultDeliveryAddress(): void
    {
        $userId = 1;
        $address = new Address();

        $addressRepositoryMock = $this->createMock(AddressRepository::class);
        $addressRepositoryMock->expects($this->once())
            ->method('getDefaultForUser')
            ->with($userId)
            ->willReturn($address);

        $addressService = new AddressBookService($addressRepositoryMock);

        $result = $addressService->getDefaultDeliveryAddress($userId);

        $this->assertEquals($address, $result);
    }
}
