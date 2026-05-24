# Architecture - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-24
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

## 8. RBAC Integration (ksfraser/rbac)

### 8.1 RBAC Integration Overview

ksf_CRM integrates with ksfraser/rbac for all access control. The RBAC model enforces:

- **Teams-only principals**: Every access grant targets teams, never individual users. Each user has an `{userId}_individual` team for personal grants.
- **SQL JOIN enforcement**: Record visibility is structural via JOIN against `0_rbac_record_access` + `0_rbac_team_members`. No secondary permission check.
- **Default deny**: Absence of an RBAC JOIN row means no access. No row = no record visible.
- **Type-level capabilities**: `can_create`, `can_hard_delete`, `can_view_deleted` stored in `0_rbac_role_permissions`.
- **Instance-level capabilities**: `can_view`, `can_edit`, `can_delete`, `can_export`, `can_print`, `can_invite`, `can_restore` stored in `0_rbac_record_access` xref rows.

### 8.2 Module Registration

CRM registers the following record types with ksfraser/rbac:

| Record Type | Parent | Children | allow_invite |
|-------------|--------|----------|--------------|
| `customer` | — | contact, opportunity, communication | false |
| `contact` | customer | — | false |
| `opportunity` | customer | — | false |
| `communication` | customer | — | false |

All CRM record types disallow invite (invite-only reserved for calendar).

### 8.3 Entity Projections

Each entity defines named DTO projections controlling field visibility:

#### Customer
| Projection | Visible Fields |
|------------|----------------|
| PUBLIC | name, phone, email, status |
| ACCOUNT | PUBLIC + credit_rating, ar_balance, segment |
| FULL | ACCOUNT + all sensitive fields |

#### Contact
| Projection | Visible Fields |
|------------|----------------|
| PUBLIC | name, email, phone |
| FULL | all fields including address, PII |

#### Opportunity
| Projection | Visible Fields |
|------------|----------------|
| PUBLIC | name, stage, probability, amount |
| FULL | all fields including notes, internal comments |

#### Communication
| Projection | Visible Fields |
|------------|----------------|
| PUBLIC | subject, type, date, direction |
| FULL | all fields including description, outcome |

### 8.4 SQL Enforcement Pattern

Every CRM entity query includes a mandatory JOIN against RBAC tables:

```sql
SELECT c.*
FROM crm_customer c
JOIN 0_rbac_record_access ra
    ON ra.record_type = 'customer'
    AND ra.record_id = c.id
    AND ra.capability = 'can_view'
JOIN 0_rbac_team_members tm
    ON tm.team_id = ra.team_id
    AND tm.user_id = :current_user_id
WHERE c.deleted = 0;
```

No row returned from this JOIN = no access. Child entities (contact, opportunity, communication) inherit visibility through the parent customer record or have their own `0_rbac_record_access` entries.

### 8.5 Capability Mapping

| CRM Operation | RBAC Capability | Level |
|---------------|-----------------|-------|
| View record | `can_view` | Instance |
| Edit record | `can_edit` | Instance |
| Delete record | `can_delete` | Instance |
| Export record | `can_export` | Instance |
| Print record | `can_print` | Instance |
| Create record | `can_create` | Type |
| Hard delete | `can_hard_delete` | Type |
| View deleted | `can_view_deleted` | Type |
| Restore deleted | `can_restore` | Instance |

### 8.6 Soft Delete Pattern

All CRM deletes are soft deletes:

```sql
UPDATE crm_customer SET deleted = 1, deleted_at = NOW(), deleted_by = :user_id WHERE id = :id;
```

Records with `deleted = 1` are excluded from all standard queries. Only users with `can_view_deleted` type-level capability may query soft-deleted records. The `can_restore` instance-level capability allows setting `deleted = 0`.

### 8.7 Switch-Role Elevation

Users may elevate their access per-record to a higher role they hold in the team hierarchy. The default active role is the least permissive of all roles a user holds. Elevation:
- Is per-record scope (does not change the session-wide active role)
- Is always written to the audit log
- Requires re-authentication if the target role has `requires_reauth = 1`
- Is available only for roles the user actually holds

### 8.8 Audit Logging

Every RBAC permission grant, revoke, denial, and elevation is logged to the audit trail via ksfraser/rbac's `AuditLoggerInterface`. CRM-level events (customer.created, opportunity.stage_changed) continue to use PSR-14 as defined in section 6.3.

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
*Last Updated: 2026-05-24*