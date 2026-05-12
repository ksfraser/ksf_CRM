# Test Plan - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Implemented
- **Author**: KSFII Development Team

---

## 1. Overview

### 1.1 Purpose
This test plan defines the testing strategy for ksf_CRM module, covering customer management, contact handling, opportunity pipeline, and communication tracking.

### 1.2 Scope
- Entity behavior testing
- Service layer testing
- Stage transition testing
- Integration with ksf_FA_CRM, ksf_EmailManager, ksf_SupportTickets

---

## 2. Test Strategy

### 2.1 Test Pyramid
```
        ┌─────────────┐
        │  Acceptance │  ← UAT scenarios
        ├─────────────┤
        │ Integration │  ← Service + DB adapter
        ├─────────────┤
        │    Unit     │  ← Entity, Service isolated
        └─────────────┘
```

### 2.2 Coverage Targets
| Layer | Target |
|-------|--------|
| Entity | 100% |
| Service | 90% |
| Events | 100% |
| Integration | 80% |

---

## 3. Unit Tests

### 3.1 Customer Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-CUST-001 | Create customer with required fields | Customer created with ID |
| CRM-CUST-002 | Create customer without name | ValidationException |
| CRM-CUST-003 | Set debtor number | debtorNo set |
| CRM-CUST-004 | Set customer type | Type assigned |
| CRM-CUST-005 | Set customer segment | Segment assigned |
| CRM-CUST-006 | Set account manager | Manager assigned |
| CRM-CUST-007 | Calculate lifetime value | Returns 0 (no orders yet) |
| CRM-CUST-008 | Get primary contact | Returns contact with isPrimary=true |
| CRM-CUST-009 | Add contact to customer | Contact linked |
| CRM-CUST-010 | Deactivate customer | Status = inactive |
| CRM-CUST-011 | Reactivate customer | Status = active |
| CRM-CUST-012 | Check active status | Returns boolean |

### 3.2 Contact Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-CONT-001 | Create contact with required fields | Contact created |
| CRM-CONT-002 | Create contact without email | ValidationException |
| CRM-CONT-003 | Get full name | Returns "FirstName LastName" |
| CRM-CONT-004 | Set as primary contact | isPrimary = true |
| CRM-CONT-005 | Only one primary per customer | ValidationException |
| CRM-CONT-006 | Deactivate contact | Status = inactive |
| CRM-CONT-007 | Set contact role | Role assigned |

### 3.3 Opportunity Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-OPP-001 | Create opportunity with required fields | Opportunity created |
| CRM-OPP-002 | Create opportunity without amount | ValidationException |
| CRM-OPP-003 | Set probability 0-100 | Probability set |
| CRM-OPP-004 | Set probability > 100 | ValidationException |
| CRM-OPP-005 | Advance stage | Stage advanced |
| CRM-OPP-006 | Close as won | Status = closed_won, closedDate set |
| CRM-OPP-007 | Close as lost | Status = closed_lost, closedReason set |
| CRM-OPP-008 | Calculate weighted value | Returns amount * probability |
| CRM-OPP-009 | Check stale (no activity > 14 days) | Returns true |
| CRM-OPP-010 | Check not stale | Returns false |
| CRM-OPP-011 | Set expected close date | Date set |
| CRM-OPP-012 | Update assigned user | assignedTo updated |

### 3.4 Communication Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-COMM-001 | Create communication with type | Type set |
| CRM-COMM-002 | Create communication without subject | ValidationException |
| CRM-COMM-003 | Check inbound | Returns direction = inbound |
| CRM-COMM-004 | Check outbound | Returns direction = outbound |
| CRM-COMM-005 | Get summary | Returns truncated description |
| CRM-COMM-006 | Link to opportunity | opportunityId set |

---

## 4. Service Layer Tests

### 4.1 CustomerService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-SVC-CUST-001 | Create customer with all fields | Customer persisted, event dispatched |
| CRM-SVC-CUST-002 | Create customer linked to FA debtor | debtorNo set |
| CRM-SVC-CUST-003 | Get customer by ID | Returns Customer or null |
| CRM-SVC-CUST-004 | Update customer details | Customer updated |
| CRM-SVC-CUST-005 | Deactivate customer | Status changed, no hard delete |
| CRM-SVC-CUST-006 | Search customers by name | Returns matching customers |
| CRM-SVC-CUST-007 | Get customers by segment | Returns filtered array |
| CRM-SVC-CUST-008 | Get customers by territory | Returns filtered array |
| CRM-SVC-CUST-009 | Get open tickets for customer | Returns ticket array |

### 4.2 ContactService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-SVC-CONT-001 | Add contact to customer | Contact created, linked |
| CRM-SVC-CONT-002 | Update contact details | Contact updated |
| CRM-SVC-CONT-003 | Set primary contact | Previous primary cleared |
| CRM-SVC-CONT-004 | Bulk import contacts | ImportResult with count |
| CRM-SVC-CONT-005 | Deduplicate contacts | Merged duplicates |

### 4.3 OpportunityService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-SVC-OPP-001 | Create opportunity | Opportunity created, linked |
| CRM-SVC-OPP-002 | Advance through stages | Stage progresses |
| CRM-SVC-OPP-003 | Close won - creates project | ProjectService.create() called |
| CRM-SVC-OPP-004 | Close lost | Status = closed_lost |
| CRM-SVC-OPP-005 | Get pipeline view | Returns grouped by stage |
| CRM-SVC-OPP-006 | Get revenue forecast | Returns weighted amounts |
| CRM-SVC-OPP-007 | Get stale opportunities | Returns needs attention |
| CRM-SVC-OPP-008 | Reopen closed opportunity | ValidationException |

### 4.4 CommunicationService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-SVC-COMM-001 | Log communication | Communication created |
| CRM-SVC-COMM-002 | Log call with outcome | Outcome recorded |
| CRM-SVC-COMM-003 | Get customer timeline | Returns sorted communications |
| CRM-SVC-COMM-004 | Filter by type | Returns filtered |
| CRM-SVC-COMM-005 | Filter by date range | Returns filtered |
| CRM-SVC-COMM-006 | Sync from email | Communication created |
| CRM-SVC-COMM-007 | Get activity summary | Returns stats object |

---

## 5. Event Tests

### 5.1 Event Dispatching Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-EVENT-001 | Create customer dispatches event | CustomerCreatedEvent fired |
| CRM-EVENT-002 | Update customer dispatches event | CustomerUpdatedEvent fired |
| CRM-EVENT-003 | Create opportunity dispatches event | OpportunityCreatedEvent fired |
| CRM-EVENT-004 | Stage change dispatches event | OpportunityStageChangedEvent fired |
| CRM-EVENT-005 | Close won dispatches event | OpportunityWonEvent fired |
| CRM-EVENT-006 | Close lost dispatches event | OpportunityLostEvent fired |
| CRM-EVENT-007 | Log communication dispatches event | CommunicationLoggedEvent fired |

---

## 6. Integration Tests

### 6.1 ksf_FA_CRM Integration

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-INT-FA-001 | Read customer from FA debtors | Returns with debtorNo |
| CRM-INT-FA-002 | Write extended CRM fields to FA | Data synced |
| CRM-INT-FA-003 | Sync contact changes | Contact updated in FA |

### 6.2 ksf_EmailManager Integration

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-INT-EMAIL-001 | Get customer email addresses | Returns email array |
| CRM-INT-EMAIL-002 | Import email to timeline | Communication created |
| CRM-INT-EMAIL-003 | Send email notification | EmailService.send() called |

### 6.3 ksf_SupportTickets Integration

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-INT-TICKET-001 | Get open tickets for customer | Returns ticket array |
| CRM-INT-TICKET-002 | Link ticket to customer | ticket.customerId set |
| CRM-INT-TICKET-003 | Ticket resolution notification | Customer updated |

### 6.4 ksf_ProjectManagement Integration

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CRM-INT-PROJ-001 | Get projects for customer | Returns project array |
| CRM-INT-PROJ-002 | Close won triggers project | ProjectService.create() called |
| CRM-INT-PROJ-003 | Project status update notification | Customer notified |

---

## 7. Test Data

### 7.1 Fixtures

```php
$customerData = [
    'id' => 'cust-001',
    'name' => 'Acme Corporation',
    'debtor_no' => 12345,
    'customer_type_id' => 'type-enterprise',
    'segment_id' => 'seg-enterprise',
    'industry' => 'Manufacturing',
    'account_manager' => 'user-001',
    'credit_rating' => 'excellent',
    'status' => 'active'
];

$contactData = [
    'id' => 'cont-001',
    'customer_id' => 'cust-001',
    'first_name' => 'John',
    'last_name' => 'Smith',
    'email' => 'john.smith@acme.com',
    'phone' => '+1-555-0100',
    'is_primary' => true
];

$opportunityData = [
    'id' => 'opp-001',
    'customer_id' => 'cust-001',
    'name' => 'Enterprise License Deal',
    'amount' => 150000.00,
    'probability' => 75,
    'stage' => 'negotiation',
    'assigned_to' => 'user-001'
];
```

---

## 8. Test Execution

### 8.1 Commands

```bash
# Run all tests
composer test

# Run unit tests only
./vendor/bin/phpunit tests/Unit

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

---

## 9. Defect Management

### 9.1 Severity Levels

| Level | Definition | SLA |
|-------|------------|-----|
| Critical | System unusable, data loss | 24h |
| High | Core feature broken | 48h |
| Medium | Feature impaired | 1 week |
| Low | Cosmetic/minor | 2 weeks |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*