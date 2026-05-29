<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Event;

use Ksfraser\CRM\Entity\Customer;
use Ksfraser\CRM\Event\CustomerUpdatedEvent;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CustomerUpdatedEvent.
 *
 * @since 1.0.0
 */
class CustomerUpdatedEventTest extends TestCase
{
    public function testGetCustomerReturnsInjectedCustomer(): void
    {
        $customer = new Customer();
        $event = new CustomerUpdatedEvent($customer);

        $this->assertSame($customer, $event->getCustomer());
    }
}
