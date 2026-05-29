<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Service;

use Ksfraser\CRM\Entity\Customer;
use Ksfraser\CRM\Entity\Contact;
use Ksfraser\CRM\Event\CustomerCreatedEvent;
use Ksfraser\CRM\Event\CustomerUpdatedEvent;

class CustomerService
{
    private array $customers = [];
    private array $contacts = [];
    private $eventDispatcher = null;

    public function setEventDispatcher($dispatcher): void
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function createCustomer(array $data): Customer
    {
        $customer = Customer::fromArray($data);
        $customer->setId($data['id'] ?? uniqid('cust_'));
        $customer->setCreatedAt(new \DateTime());
        $customer->setUpdatedAt(new \DateTime());

        $this->customers[$customer->getId()] = $customer;

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(new CustomerCreatedEvent($customer));
        }

        return $customer;
    }

    public function getCustomer(string $id): ?Customer
    {
        return $this->customers[$id] ?? null;
    }

    public function updateCustomer(string $id, array $data): Customer
    {
        $customer = $this->getCustomer($id);
        if (!$customer) {
            throw new \RuntimeException("Customer not found: {$id}");
        }

        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($customer, $method)) {
                $customer->$method($value);
            }
        }
        $customer->setUpdatedAt(new \DateTime());

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(new CustomerUpdatedEvent($customer));
        }

        return $customer;
    }

    public function deleteCustomer(string $id): bool
    {
        if (!isset($this->customers[$id])) {
            return false;
        }
        unset($this->customers[$id]);
        return true;
    }

    public function searchCustomers(string $query): array
    {
        $query = strtolower($query);
        return array_filter(
            $this->customers,
            fn(Customer $c) => 
                stripos($c->getName(), $query) !== false ||
                stripos($c->getIndustry(), $query) !== false
        );
    }

    public function getCustomersBySegment(string $segmentId): array
    {
        return array_filter(
            $this->customers,
            fn(Customer $c) => $c->getSegmentId() === $segmentId
        );
    }

    public function getCustomersByTerritory(string $territoryId): array
    {
        return array_filter(
            $this->customers,
            fn(Customer $c) => $c->getTerritoryId() === $territoryId
        );
    }

    public function getActiveCustomers(): array
    {
        return array_filter(
            $this->customers,
            fn(Customer $c) => $c->isActive()
        );
    }

    public function addContact(string $customerId, array $data): Contact
    {
        $customer = $this->getCustomer($customerId);
        if (!$customer) {
            throw new \RuntimeException("Customer not found: {$customerId}");
        }

        $contact = Contact::fromArray($data);
        $contact->setId($data['id'] ?? uniqid('cont_'));
        $contact->setCustomerId($customerId);
        $contact->setCreatedAt(new \DateTime());
        $contact->setUpdatedAt(new \DateTime());

        $this->contacts[$contact->getId()] = $contact;
        $customer->addContact($contact);

        return $contact;
    }

    public function getContact(string $id): ?Contact
    {
        return $this->contacts[$id] ?? null;
    }

    public function getContactsByCustomer(string $customerId): array
    {
        return array_filter(
            $this->contacts,
            fn(Contact $c) => $c->getCustomerId() === $customerId
        );
    }

    public function setPrimaryContact(string $contactId): bool
    {
        $contact = $this->getContact($contactId);
        if (!$contact) {
            return false;
        }

        $customerContacts = $this->getContactsByCustomer($contact->getCustomerId());
        foreach ($customerContacts as $c) {
            if ($c->getId() !== $contactId) {
                $c->setIsPrimary(false);
            }
        }
        $contact->setIsPrimary(true);

        return true;
    }

    public function getCustomerByDebtorNo(int $debtorNo): ?Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->getDebtorNo() === $debtorNo) {
                return $customer;
            }
        }
        return null;
    }
}