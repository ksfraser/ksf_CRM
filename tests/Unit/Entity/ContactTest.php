<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Entity;

use Ksfraser\CRM\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    private Contact $contact;

    protected function setUp(): void
    {
        $this->contact = new Contact();
    }

    public function testSetAndGetCustomerId(): void
    {
        $result = $this->contact->setCustomerId('cust_123');
        $this->assertSame($this->contact, $result);
        $this->assertSame('cust_123', $this->contact->getCustomerId());
    }

    public function testSetAndGetFirstName(): void
    {
        $result = $this->contact->setFirstName('John');
        $this->assertSame($this->contact, $result);
        $this->assertSame('John', $this->contact->getFirstName());
    }

    public function testSetAndGetLastName(): void
    {
        $result = $this->contact->setLastName('Doe');
        $this->assertSame($this->contact, $result);
        $this->assertSame('Doe', $this->contact->getLastName());
    }

    public function testSetAndGetEmail(): void
    {
        $result = $this->contact->setEmail('john@example.com');
        $this->assertSame($this->contact, $result);
        $this->assertSame('john@example.com', $this->contact->getEmail());
    }

    public function testSetAndGetPhone(): void
    {
        $result = $this->contact->setPhone('555-1234');
        $this->assertSame($this->contact, $result);
        $this->assertSame('555-1234', $this->contact->getPhone());
    }

    public function testSetAndGetIsPrimary(): void
    {
        $this->assertFalse($this->contact->isPrimary());
        $this->contact->setIsPrimary(true);
        $this->assertTrue($this->contact->isPrimary());
    }

    public function testGetFullName(): void
    {
        $this->contact->setFirstName('John');
        $this->contact->setLastName('Doe');
        $this->assertSame('John Doe', $this->contact->getFullName());
    }

    public function testToArrayReturnsAllFields(): void
    {
        $this->contact->setId('cont_123');
        $this->contact->setCustomerId('cust_456');
        $this->contact->setFirstName('Jane');
        $this->contact->setLastName('Smith');
        $this->contact->setEmail('jane@example.com');
        $this->contact->setIsPrimary(true);

        $array = $this->contact->toArray();

        $this->assertSame('cont_123', $array['id']);
        $this->assertSame('cust_456', $array['customer_id']);
        $this->assertSame('Jane', $array['first_name']);
        $this->assertSame('Smith', $array['last_name']);
        $this->assertSame('jane@example.com', $array['email']);
        $this->assertTrue($array['is_primary']);
    }

    public function testFromArrayCreatesContact(): void
    {
        $data = [
            'id' => 'cont_789',
            'customer_id' => 'cust_001',
            'first_name' => 'Bob',
            'last_name' => 'Wilson',
            'email' => 'bob@company.com',
            'is_primary' => true,
        ];

        $contact = Contact::fromArray($data);

        $this->assertSame('cont_789', $contact->getId());
        $this->assertSame('cust_001', $contact->getCustomerId());
        $this->assertSame('Bob', $contact->getFirstName());
        $this->assertSame('Wilson', $contact->getLastName());
        $this->assertSame('bob@company.com', $contact->getEmail());
        $this->assertTrue($contact->isPrimary());
    }
}
