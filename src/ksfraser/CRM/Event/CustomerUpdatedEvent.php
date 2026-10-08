<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Event;

use Ksfraser\CRM\Entity\Customer;

class CustomerUpdatedEvent
{
    private Customer $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}