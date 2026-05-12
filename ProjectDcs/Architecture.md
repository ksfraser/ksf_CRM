# Architecture - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Module Overview

ksf_CRM provides comprehensive customer relationship management including customer profiles, multi-contact management, sales opportunities, and communication tracking.

### 1.1 Namespace
```php
Ksfraser\CRM\
```

### 1.2 Layer Pattern
```
ksf_CRM/                  → Business Logic
    ├── Entity/            → Domain entities
    ├── Service/           → Business services
    ├── Repository/         → Data access interfaces
    ├── Contract/          → Interfaces for adapters
    ├── Exception/          → Domain exceptions
    └── Event/             → PSR-14 events
```

---

## 2. Core Entities

### 2.1 Customer (Aggregate Root)

```php
class Customer {
    private string $id;
    private ?int $debtorNo;           // FA integration
    private string $name;
    private ?CustomerType $type;
    private ?CustomerSegment $segment;
    private ?Territory $territory;
    private string $industry;
    private string $website;
    private int $employeeCount;
    private float $annualRevenue;
    private ?string $accountManagerId;
    private CreditRating $creditRating;
    private ?\DateTime $customerSince;
    private ?\DateTime $lastContactDate;
    private string $preferredContactMethod;
    private CustomerStatus $status;
    
    // Collections
    private Collection $contacts;
    private Collection $opportunities;
    private Collection $communications;
    private Collection $documents;
    
    // Methods
    public function addContact(Contact $contact): self;
    public function createOpportunity(array $data): Opportunity;
    public function getPrimaryContact(): ?Contact;
    public function calculateLifetimeValue(): float;
    public function getOpenTickets(): array;
}
```

### 2.2 Contact

```php
class Contact {
    private string $id;
    private string $customerId;
    private ?ContactRole $role;
    private string $firstName;
    private string $lastName;
    private string $title;
    private string $department;
    private string $email;
    private string $phone;
    private string $mobile;
    private bool $isPrimary;
    private ContactStatus $status;
    
    // Methods
    public function getFullName(): string;
    public function getEmail(): string;
    public function setAsPrimary(): self;
}
```

### 2.3 Opportunity

```php
class Opportunity {
    private string $id;
    private string $customerId;
    private string $name;
    private float $amount;
    private int $probability;          // 0-100
    private OpportunityStage $stage;
    private ?\DateTime $expectedCloseDate;
    private ?string $leadSource;
    private ?string $campaignId;
    private string $assignedTo;
    private ?\DateTime $closedDate;
    private ?string $closedReason;
    
    // Methods
    public function advanceStage(): self;
    public function closeWon(string $reason = null): self;
    public function closeLost(string $reason): self;
    public function calculateWeightedValue(): float;
    public function isStale(): bool;   // No activity > 14 days
}
```

### 2.4 Communication

```php
class Communication {
    private string $id;
    private string $customerId;
    private ?string $contactId;
    private CommunicationType $type;
    private string $subject;
    private ?string $description;
    private CommunicationDirection $direction;
    private ?string $outcome;
    private ?string $opportunityId;
    private string $userId;
    private \DateTime $occurredAt;
    
    // Methods
    public function isInbound(): bool;
    public function getSummary(): string;
}
```

---

## 3. Entity Relationships

```
Customer (1) ──────┬─────< Contact (many)
                   │
                   ├─────< Opportunity (many)
                   │
                   ├─────< Communication (many)
                   │
                   └─────< Document (many, via ksf_Documents)
```

---

## 4. State Machines

### 4.1 Customer Status

```
Active ───> Inactive
    │
    └── (reactivation) ───> Active
```

### 4.2 Opportunity Stage

```
Prospecting ──> Qualification ──> Needs Analysis
      │              │                │
      ▼              ▼                ▼
  Proposal ───> Negotiation ──┬──> Closed Won
       │          │           │
       │          │           └──> Closed Lost
       │          │
       └──────────┘
    (back to any stage)
```

### 4.3 Contact Status

```
Active ───> Inactive
```

---

## 5. Service Layer

### 5.1 CustomerService

| Method | Description |
|--------|-------------|
| `createCustomer(array $data): Customer` | Create new customer |
| `getCustomer(string $id): ?Customer` | Retrieve customer |
| `updateCustomer(string $id, array $data): Customer` | Update customer |
| `deactivateCustomer(string $id): bool` | Soft delete |
| `searchCustomers(string $query): array` | Full-text search |
| `getCustomersBySegment(string $segmentId): array` | Segment filter |
| `getCustomersByTerritory(string $territoryId): array` | Territory filter |

### 5.2 ContactService

| Method | Description |
|--------|-------------|
| `addContact(string $customerId, array $data): Contact` | Add contact |
| `getContact(string $id): ?Contact` | Retrieve contact |
| `updateContact(string $id, array $data): Contact` | Update contact |
| `setPrimaryContact(string $contactId): bool` | Set primary |
| `importContacts(array $contacts): ImportResult` | Bulk import |
| `deduplicateContacts(string $customerId): int` | Find & merge |

### 5.3 OpportunityService

| Method | Description |
|--------|-------------|
| `createOpportunity(string $customerId, array $data): Opportunity` | Create opportunity |
| `getOpportunity(string $id): ?Opportunity` | Retrieve |
| `advanceStage(string $id): Opportunity` | Move to next stage |
| `closeOpportunity(string $id, bool $won, string $reason): Opportunity` | Close deal |
| `getPipeline(string $assignedTo = null): array` | Get pipeline view |
| `getForecast(float $period): array` | Revenue forecast |
| `getStaleOpportunities(int $days = 14): array` | Needs attention |

### 5.4 CommunicationService

| Method | Description |
|--------|-------------|
| `logCommunication(string $customerId, array $data): Communication` | Log activity |
| `getCommunications(string $customerId, array $filters = []): array` | Get timeline |
| `getActivitySummary(string $customerId): array` | Summary stats |
| `syncFromEmail(array $emailData): Communication` | Import from email |

---

## 6. Integration Architecture

### 6.1 Provided Services

| Consumer | Interface | Data |
|----------|-----------|------|
| ksf_FA_CRM | CustomerServiceInterface | Customers, contacts |
| ksf_SupportTickets | CustomerServiceInterface | Customer lookup |
| ksf_ProjectManagement | CustomerServiceInterface | Customer for projects |
| ksf_EmailManager | CustomerServiceInterface | Email addresses |
| ksf_Calendar | CustomerServiceInterface | Customer events |
| ksf_Documents | CustomerServiceInterface | Customer documents |

### 6.2 Consumed Services

| Provider | Interface | Data |
|----------|-----------|------|
| ksf_Workflow | WorkflowTriggerInterface | Automation triggers |
| ksf_Calendar | CalendarServiceInterface | Meeting sync |
| ksf_EmailManager | EmailServiceInterface | Email import |
| ksf_SupportTickets | TicketServiceInterface | Ticket updates |
| ksf_ProjectManagement | ProjectServiceInterface | Project status |

### 6.3 Event Flow

```
Sales Activity → OpportunityService → opportunity.stage_changed →
    → ksf_Workflow (automation) →
    → ksf_EmailManager (notification) →
    → ksf_Calendar (follow-up)

Customer Update → CustomerService → customer.updated →
    → ksf_SupportTickets (history) →
    → ksf_Documents (context) →
    → ksf_ProjectManagement (customer change)
```

---

## 7. FA Integration (ksf_FA_CRM)

### 7.1 Data Synchronization

| FA Table | CRM Entity | Direction |
|----------|-----------|-----------|
| debtors | Customer | FA → CRM (read) |
| CRM_DATA | Customer | Bidirectional (extended) |
| contacts | Contact | CRM → FA (write) |

### 7.2 Debtor Mapping

```php
class Customer {
    private ?int $debtorNo;  // Links to FA debtors.debtor_no
    
    public function getDebtorNo(): ?int;
    public function hasDebtorNo(): bool;
}
```

---

## 8. Security & Access Control

### 8.1 Access Levels

| Role | Customers | Contacts | Opportunities | Communications |
|------|-----------|----------|----------------|-----------------|
| Admin | All | All | All | All |
| Sales Manager | All | All | All | All |
| Sales Rep | Assigned | Assigned | Assigned | Assigned |
| Support | Assigned | Assigned | - | Assigned |
| Marketing | Segment | Segment | - | Segment |

### 8.2 Field-Level Security

- Credit rating: Admin, Sales Manager only
- Financial data: Admin, Finance only
- Account manager assignment: Admin, Sales Manager only

---

## 9. Performance Considerations

### 9.1 Caching Strategy
- Customer list: 5-min TTL
- Opportunity pipeline: Real-time (small dataset)
- Communication timeline: 1-min TTL

### 9.2 Query Optimization
- Customer search: Indexed columns (name, segment, territory)
- Opportunity pipeline: Indexed by stage, assigned_to
- Activity timeline: Date-based indexing

### 9.3 Async Operations
- Email sync: Queue for bulk imports
- Report generation: Background job
- Follow-up notifications: Cron-based

---

## 10. Error Handling

### 10.1 Exception Hierarchy

```
\Exception
└── RuntimeException
    └── Ksfraser\Exceptions\CRM\CRMException (base)
        ├── CRMCustomerNotFoundException
        ├── CRMContactNotFoundException
        ├── CRMOpportunityNotFoundException
        ├── CRMValidationException
        ├── CRMDuplicateContactException
        └── CRMStageTransitionException
```

### 10.2 Error Responses

| Exception | HTTP Code | Message |
|-----------|-----------|---------|
| CRMCustomerNotFoundException | 404 | Customer not found |
| CRMContactNotFoundException | 404 | Contact not found |
| CRMOpportunityNotFoundException | 404 | Opportunity not found |
| CRMValidationException | 400 | Invalid input data |
| CRMStageTransitionException | 409 | Invalid stage transition |

---

## 11. File Structure

```
ksf_CRM/
├── composer.json
├── AGENTS.md
├── ProjectDcs/
│   ├── Business Requirements.md
│   ├── Architecture.md           ← THIS FILE
│   ├── Functional Requirements.md
│   ├── Use Case.md
│   ├── Test Plan.md
│   ├── UAT Plan.md
│   └── RTM.md
└── src/Ksfraser/CRM/
    ├── Entity/
    │   ├── Customer.php
    │   ├── Contact.php
    │   ├── Opportunity.php
    │   ├── Communication.php
    │   └── ValueObject/
    ├── Service/
    │   ├── CustomerService.php
    │   ├── ContactService.php
    │   ├── OpportunityService.php
    │   └── CommunicationService.php
    ├── Repository/
    │   └── CustomerRepositoryInterface.php
    ├── Contract/
    │   └── CustomerServiceInterface.php
    ├── Exception/
    │   ├── CRMException.php
    │   ├── CustomerNotFoundException.php
    │   └── ValidationException.php
    └── Event/
        ├── CustomerCreatedEvent.php
        └── OpportunityStageChangedEvent.php
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*