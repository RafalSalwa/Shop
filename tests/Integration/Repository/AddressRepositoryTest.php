<?php

declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Address;
use App\Repository\AddressRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[CoversClass(className: AddressRepository::class)]
#[UsesClass(className: Address::class)]
final class AddressRepositoryTest extends WebTestCase
{
    private AddressRepository $addressRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->addressRepository = self::getContainer()->get(AddressRepository::class);
    }

    public function testSaveAddress(): void
    {
        // Create a new address
        $address = new Address(1);
        $address->setUserId(1);
        $address->setCity('Springfield');
        $address->setDefault(true);

        // Save the address
        $this->addressRepository->save($address);

        // Retrieve the saved address from the repository
        $savedAddress = $this->addressRepository->find($address->getId());

        // Assert that the saved address exists
        $this->assertInstanceOf(Address::class, $savedAddress);
        $this->assertSame('Springfield', $savedAddress->getCity());
        $this->assertTrue($savedAddress->isDefault());
        $this->addressRepository->setDefaultAddress($address->getId(), 1);
    }

    public function testGetDefaultForUser(): void
    {
        // Retrieve the default address for the user
        $defaultAddress = $this->addressRepository->getDefaultForUser(1);

        // Assert that the correct default address is returned
        $this->assertInstanceOf(Address::class, $defaultAddress);
        $this->assertSame('Springfield', $defaultAddress->getCity());
    }
}
