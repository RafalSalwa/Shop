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

    public function save(mixed $address): void
    {
        $this->addressRepository->save($address);
    }

    /** @return array<Address> */
    public function getDeliveryAddresses(int $userId): array
    {
        return $this->addressRepository->findBy(['userId' => $userId]);
    }
}
