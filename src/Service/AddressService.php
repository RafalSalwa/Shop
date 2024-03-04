<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\AddressRepository;

final class AddressService
{
    public function __construct(private AddressRepository $addressRepository)
    {
    }

    public function save(mixed $address): void
    {
        $this->addressRepository->save($address);
    }
}
