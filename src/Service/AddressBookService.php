<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Address;
use App\Repository\AddressRepository;

final readonly class AddressBookService
{
    public function __construct(private AddressRepository $addressRepository)
    {
    }

    public function save(Address $address): void
    {
        $this->addressRepository->save($address);
        $this->setDefaultAddress($address->getId(), $address->getUserId());
    }

    public function setDefaultAddress(int $addressId, int $userId): void
    {
        $this->addressRepository->setDefaultAddress($addressId, $userId);
    }

    /** @return array<array-key, Address> */
    public function getDeliveryAddresses(int $userId): array
    {
        return $this->addressRepository->findBy(['userId' => $userId]);
    }

    public function getDefaultDeliveryAddress(int $userId): ?Address
    {
        return $this->addressRepository->getDefaultForUser($userId);
    }
}
