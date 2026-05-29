<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Service;

use DateTime;
use Ksfraser\CRM\Entity\Customer;
use Ksfraser\CRM\Service\CustomerService;
use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    private CustomerService $service;

    protected function setUp(): void
    {
        $this->service = new CustomerService();
    }

    public function testCreateCustomer(): void
    {
        $data = [
            'name' => 'Acme Corp',
            'industry' => 'Technology',
            'debtor_no' => 12345,
        ];

        $customer = $this->service->createCustomer($data);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame('Acme Corp', $customer->getName());
        $this->assertSame('Technology', $customer->getIndustry());
        $this->assertSame(12345, $customer->getDebtorNo());
        $this->assertNotNull($customer->getId());
    }

    public function testGetCustomer(): void
    {
        $data = ['name' => 'Test Corp'];
        $created = $this->service->createCustomer($data);

        $retrieved = $this->service->getCustomer($created->getId());

        $this->assertSame($created, $retrieved);
    }

    public function testGetCustomerReturnsNullForNonexistent(): void
    {
        $result = $this->service->getCustomer('nonexistent');
        $this->assertNull($result);
    }

    public function testUpdateCustomer(): void
    {
        $data = ['name' => 'Original Name', 'industry' => 'Tech'];
        $customer = $this->service->createCustomer($data);

        $updated = $this->service->updateCustomer($customer->getId(), ['name' => 'Updated Name']);

        $this->assertSame('Updated Name', $updated->getName());
        $this->assertSame('Tech', $updated->getIndustry());
    }

    public function testDeleteCustomer(): void
    {
        $data = ['name' => 'To Delete'];
        $customer = $this->service->createCustomer($data);
        $id = $customer->getId();

        $result = $this->service->deleteCustomer($id);

        $this->assertTrue($result);
        $this->assertNull($this->service->getCustomer($id));
    }

    public function testSearchCustomers(): void
    {
        $this->service->createCustomer(['name' => 'Acme Corporation', 'industry' => 'Tech']);
        $this->service->createCustomer(['name' => 'Beta Inc', 'industry' => 'Finance']);
        $this->service->createCustomer(['name' => 'Gamma Corp', 'industry' => 'Tech']);

        $results = $this->service->searchCustomers('acme');

        $this->assertCount(1, $results);
    }

    public function testGetActiveCustomers(): void
    {
        $this->service->createCustomer(['name' => 'Active Corp']);
        $inactive = $this->service->createCustomer(['name' => 'Inactive Corp']);
        $this->service->updateCustomer($inactive->getId(), ['status' => Customer::STATUS_INACTIVE]);

        $active = $this->service->getActiveCustomers();

        $this->assertCount(1, $active);
    }

    public function testAddContact(): void
    {
        $customer = $this->service->createCustomer(['name' => 'Test Corp']);

        $contact = $this->service->addContact($customer->getId(), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertSame('John', $contact->getFirstName());
        $this->assertSame('john@example.com', $contact->getEmail());
        $this->assertSame($customer->getId(), $contact->getCustomerId());
    }

    public function testSetPrimaryContact(): void
    {
        $customer = $this->service->createCustomer(['name' => 'Test Corp']);
        $contact1 = $this->service->addContact($customer->getId(), ['first_name' => 'Primary', 'email' => 'p@test.com']);
        $contact2 = $this->service->addContact($customer->getId(), ['first_name' => 'Secondary', 'email' => 's@test.com']);

        $this->service->setPrimaryContact($contact1->getId());

        $this->assertTrue($contact1->isPrimary());
        $this->assertFalse($contact2->isPrimary());
    }
}
