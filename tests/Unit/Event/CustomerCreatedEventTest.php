<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Event;

use Ksfraser\CRM\Entity\Customer;
use Ksfraser\CRM\Event\CustomerCreatedEvent;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CustomerCreatedEvent.
 *
 * @since 1.0.0
 */
class CustomerCreatedEventTest extends TestCase
{
    public function testGetCustomerReturnsInjectedCustomer(): void
    {
        $customer = new Customer();
        $event = new CustomerCreatedEvent($customer);

        $this->assertSame($customer, $event->getCustomer());
    }
}
