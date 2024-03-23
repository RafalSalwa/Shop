<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

#[CoversClass(className: Address::class)]
class AddressTest extends TestCase
{
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();
        $this->address = new Address();
    }

    public function testGettersAndSetters(): void
    {
        $this->address->setFirstName('John');
        $this->assertEquals('John', $this->address->getFirstName());

        $this->address->setLastName('Doe');
        $this->assertEquals('Doe', $this->address->getLastName());

        $this->address->setAddressLine1('Street 1');
        $this->assertEquals('Street 1', $this->address->getAddressLine1());

        $this->address->setPhoneNo('123456789');
        $this->assertEquals('123456789', $this->address->getPhoneNo());

        $this->address->setState('test state');
        $this->assertEquals('test state', $this->address->getState());

        $this->address->setCity('test city');
        $this->assertEquals('test city', $this->address->getCity());

        $this->address->setDefault(true);
        $this->assertTrue($this->address->isDefault());
        $this->address->setDefault(false);
        $this->assertFalse($this->address->isDefault());

        $this->address->setPostalCode('12-312');
        $this->assertEquals('12-312', $this->address->getPostalCode());

        $this->address->setCountry('test country');
        $this->assertEquals('test country', $this->address->getCountry());

        $this->address->setUserId(1);
        $this->assertEquals('1', $this->address->getUserId());
    }

    public function testPostalCodeException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->address->setPostalCode('123123');
        $this->assertEquals(null, $this->address->getPostalCode());
    }

    public function testNotBlankConstraints(): void
    {
        $violations = $this->validateEntity($this->address);
        $this->assertEquals(6, $violations->count());

        // Set some properties to pass validation
        $this->address->setFirstName('John');
        $this->address->setLastName('Doe');
        $this->address->setAddressLine1('123 Main St');
        $this->address->setCity('New York');

        $violations = $this->validateEntity($this->address);
        $this->assertEquals(2, $violations->count());
    }

    public function testDefaultIsFalse(): void
    {
        $this->assertFalse($this->address->isDefault());
    }

    public function testNullableProperties(): void
    {
        // Test setting nullable properties
        $this->address->setAddressLine2('Apt 101');
        $this->assertEquals('Apt 101', $this->address->getAddressLine2());

        // Test setting null for nullable properties
        $this->address->setAddressLine2('');
        $this->assertEmpty($this->address->getAddressLine2());
    }

    public function testIdGetterAndSetter(): void
    {
        $this->address->setId(5);
        $this->assertEquals(5, $this->address->getId());
    }

    private function validateEntity(Address $address): \Symfony\Component\Validator\ConstraintViolationListInterface
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();

        return $validator->validate($address);
    }
}
