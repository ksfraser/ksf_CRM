<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Entity;

class Customer
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    private ?string $id = null;
    private ?int $debtorNo = null;
    private string $name = '';
    private ?string $customerTypeId = null;
    private ?string $segmentId = null;
    private ?string $territoryId = null;
    private string $industry = '';
    private string $website = '';
    private int $employeeCount = 0;
    private float $annualRevenue = 0.0;
    private ?string $accountManagerId = null;
    private string $creditRating = 'good';
    private ?\DateTime $customerSince = null;
    private ?\DateTime $lastContactDate = null;
    private string $preferredContactMethod = 'email';
    private string $status = self::STATUS_ACTIVE;
    private ?\DateTime $createdAt = null;
    private ?\DateTime $updatedAt = null;

    private array $contacts = [];
    private array $opportunities = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDebtorNo(): ?int
    {
        return $this->debtorNo;
    }

    public function setDebtorNo(?int $debtorNo): self
    {
        $this->debtorNo = $debtorNo;
        return $this;
    }

    public function hasDebtorNo(): bool
    {
        return $this->debtorNo !== null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCustomerTypeId(): ?string
    {
        return $this->customerTypeId;
    }

    public function setCustomerTypeId(?string $customerTypeId): self
    {
        $this->customerTypeId = $customerTypeId;
        return $this;
    }

    public function getSegmentId(): ?string
    {
        return $this->segmentId;
    }

    public function setSegmentId(?string $segmentId): self
    {
        $this->segmentId = $segmentId;
        return $this;
    }

    public function getTerritoryId(): ?string
    {
        return $this->territoryId;
    }

    public function setTerritoryId(?string $territoryId): self
    {
        $this->territoryId = $territoryId;
        return $this;
    }

    public function getIndustry(): string
    {
        return $this->industry;
    }

    public function setIndustry(string $industry): self
    {
        $this->industry = $industry;
        return $this;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;
        return $this;
    }

    public function getEmployeeCount(): int
    {
        return $this->employeeCount;
    }

    public function setEmployeeCount(int $employeeCount): self
    {
        $this->employeeCount = $employeeCount;
        return $this;
    }

    public function getAnnualRevenue(): float
    {
        return $this->annualRevenue;
    }

    public function setAnnualRevenue(float $annualRevenue): self
    {
        $this->annualRevenue = $annualRevenue;
        return $this;
    }

    public function getAccountManagerId(): ?string
    {
        return $this->accountManagerId;
    }

    public function setAccountManagerId(?string $accountManagerId): self
    {
        $this->accountManagerId = $accountManagerId;
        return $this;
    }

    public function getCreditRating(): string
    {
        return $this->creditRating;
    }

    public function setCreditRating(string $creditRating): self
    {
        $this->creditRating = $creditRating;
        return $this;
    }

    public function getCustomerSince(): ?\DateTime
    {
        return $this->customerSince;
    }

    public function setCustomerSince(?\DateTime $customerSince): self
    {
        $this->customerSince = $customerSince;
        return $this;
    }

    public function getLastContactDate(): ?\DateTime
    {
        return $this->lastContactDate;
    }

    public function setLastContactDate(?\DateTime $lastContactDate): self
    {
        $this->lastContactDate = $lastContactDate;
        return $this;
    }

    public function getPreferredContactMethod(): string
    {
        return $this->preferredContactMethod;
    }

    public function setPreferredContactMethod(string $preferredContactMethod): self
    {
        $this->preferredContactMethod = $preferredContactMethod;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function activate(): self
    {
        $this->status = self::STATUS_ACTIVE;
        return $this;
    }

    public function deactivate(): self
    {
        $this->status = self::STATUS_INACTIVE;
        return $this;
    }

    public function isVip(): bool
    {
        return $this->annualRevenue >= 500000 || $this->employeeCount >= 100;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        $this->contacts[] = $contact;
        return $this;
    }

    public function getOpportunities(): array
    {
        return $this->opportunities;
    }

    public function addOpportunity(Opportunity $opportunity): self
    {
        $this->opportunities[] = $opportunity;
        return $this;
    }

    public function getPrimaryContact(): ?Contact
    {
        foreach ($this->contacts as $contact) {
            if ($contact->isPrimary()) {
                return $contact;
            }
        }
        return $this->contacts[0] ?? null;
    }

    public function calculateLifetimeValue(): float
    {
        return $this->annualRevenue * ($this->customerSince ? $this->getCustomerAge() : 1);
    }

    private function getCustomerAge(): float
    {
        if (!$this->customerSince) {
            return 1;
        }
        $now = new \DateTime();
        return max(1, $now->diff($this->customerSince)->y + 1);
    }

    public function getOpenOpportunities(): array
    {
        return array_filter(
            $this->opportunities,
            fn(Opportunity $opp) => !$opp->isClosed()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'debtor_no' => $this->debtorNo,
            'name' => $this->name,
            'customer_type_id' => $this->customerTypeId,
            'segment_id' => $this->segmentId,
            'territory_id' => $this->territoryId,
            'industry' => $this->industry,
            'website' => $this->website,
            'employee_count' => $this->employeeCount,
            'annual_revenue' => $this->annualRevenue,
            'account_manager_id' => $this->accountManagerId,
            'credit_rating' => $this->creditRating,
            'customer_since' => $this->customerSince ? $this->customerSince->format('Y-m-d') : null,
            'last_contact_date' => $this->lastContactDate ? $this->lastContactDate->format('Y-m-d H:i:s') : null,
            'preferred_contact_method' => $this->preferredContactMethod,
            'status' => $this->status,
            'created_at' => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        $customer = new self();
        
        if (isset($data['id'])) $customer->setId($data['id']);
        if (isset($data['debtor_no'])) $customer->setDebtorNo($data['debtor_no']);
        if (isset($data['name'])) $customer->setName($data['name']);
        if (isset($data['customer_type_id'])) $customer->setCustomerTypeId($data['customer_type_id']);
        if (isset($data['segment_id'])) $customer->setSegmentId($data['segment_id']);
        if (isset($data['territory_id'])) $customer->setTerritoryId($data['territory_id']);
        if (isset($data['industry'])) $customer->setIndustry($data['industry']);
        if (isset($data['website'])) $customer->setWebsite($data['website']);
        if (isset($data['employee_count'])) $customer->setEmployeeCount($data['employee_count']);
        if (isset($data['annual_revenue'])) $customer->setAnnualRevenue((float)$data['annual_revenue']);
        if (isset($data['account_manager_id'])) $customer->setAccountManagerId($data['account_manager_id']);
        if (isset($data['credit_rating'])) $customer->setCreditRating($data['credit_rating']);
        if (isset($data['customer_since'])) $customer->setCustomerSince(new \DateTime($data['customer_since']));
        if (isset($data['last_contact_date'])) $customer->setLastContactDate(new \DateTime($data['last_contact_date']));
        if (isset($data['preferred_contact_method'])) $customer->setPreferredContactMethod($data['preferred_contact_method']);
        if (isset($data['status'])) $customer->setStatus($data['status']);
        
        return $customer;
    }
}