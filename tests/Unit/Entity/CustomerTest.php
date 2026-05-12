<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Entity;

use DateTime;
use Ksfraser\CRM\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        $this->customer = new Customer();
    }

    public function testSetAndGetName(): void
    {
        $result = $this->customer->setName('Acme Corp');
        $this->assertSame($this->customer, $result);
        $this->assertSame('Acme Corp', $this->customer->getName());
    }

    public function testSetAndGetIndustry(): void
    {
        $result = $this->customer->setIndustry('Technology');
        $this->assertSame($this->customer, $result);
        $this->assertSame('Technology', $this->customer->getIndustry());
    }

    public function testSetAndGetDebtorNo(): void
    {
        $result = $this->customer->setDebtorNo(12345);
        $this->assertSame($this->customer, $result);
        $this->assertSame(12345, $this->customer->getDebtorNo());
    }

    public function testSetAndGetSegmentId(): void
    {
        $result = $this->customer->setSegmentId('seg_001');
        $this->assertSame($this->customer, $result);
        $this->assertSame('seg_001', $this->customer->getSegmentId());
    }

    public function testSetAndGetTerritoryId(): void
    {
        $result = $this->customer->setTerritoryId('terr_west');
        $this->assertSame($this->customer, $result);
        $this->assertSame('terr_west', $this->customer->getTerritoryId());
    }

    public function testActivateDeactivate(): void
    {
        $this->assertTrue($this->customer->isActive());
        $this->customer->deactivate();
        $this->assertFalse($this->customer->isActive());
        $this->customer->activate();
        $this->assertTrue($this->customer->isActive());
    }

    public function testIsVipReturnsTrue(): void
    {
        $this->customer->setAnnualRevenue(1000000);
        $this->assertTrue($this->customer->isVip());
    }

    public function testIsVipReturnsFalse(): void
    {
        $this->customer->setAnnualRevenue(50000);
        $this->assertFalse($this->customer->isVip());
    }

    public function testToArrayReturnsAllFields(): void
    {
        $this->customer->setId('cust_123');
        $this->customer->setName('Acme Corp');
        $this->customer->setIndustry('Technology');
        $this->customer->setDebtorNo(12345);

        $array = $this->customer->toArray();

        $this->assertSame('cust_123', $array['id']);
        $this->assertSame('Acme Corp', $array['name']);
        $this->assertSame('Technology', $array['industry']);
        $this->assertSame(12345, $array['debtor_no']);
    }

    public function testFromArrayCreatesCustomer(): void
    {
        $data = [
            'id' => 'cust_456',
            'name' => 'Test Corp',
            'industry' => 'Finance',
            'debtor_no' => 99999,
        ];

        $customer = Customer::fromArray($data);

        $this->assertSame('cust_456', $customer->getId());
        $this->assertSame('Test Corp', $customer->getName());
        $this->assertSame('Finance', $customer->getIndustry());
        $this->assertSame(99999, $customer->getDebtorNo());
    }
}
