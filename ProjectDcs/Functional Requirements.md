# Functional Requirements - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Overview

### 1.1 Purpose
ksf_CRM provides comprehensive customer relationship management including customer profiles, contacts, opportunities, and communication tracking.

### 1.2 Scope
- Enhanced customer profiles
- Multi-contact management
- Sales opportunity pipeline
- Communication logging
- Activity tracking
- Calendar integration
- Document attachment

## 2. Core Entities

### 2.1 Customer

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | UUID |
| debtor_no | int | No | FA debtor number |
| name | string | Yes | Company name |
| customer_type_id | string | No | FK to CustomerType |
| customer_segment_id | string | No | FK to CustomerSegment |
| territory_id | string | No | FK to Territory |
| industry | string | No | Industry classification |
| website | string | No | URL |
| employee_count | int | No | Number of employees |
| annual_revenue | float | No | Annual revenue |
| account_manager | string | No | Assigned user ID |
| credit_rating | string | No | excellent/good/fair/poor |
| customer_since | Date | No | Relationship start |
| last_contact_date | DateTime | No | Last activity |
| preferred_contact_method | string | No | email/phone/mail |
| status | string | Yes | active/inactive |
| created_at | DateTime | Yes | Auto |
| updated_at | DateTime | Yes | Auto |

### 2.2 Contact

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | UUID |
| customer_id | string | Yes | FK to Customer |
| contact_role_id | string | No | FK to ContactRole |
| first_name | string | Yes | First name |
| last_name | string | Yes | Last name |
| title | string | No | Job title |
| department | string | No | Department |
| email | string | Yes | Email address |
| phone | string | No | Phone |
| mobile | string | No | Mobile |
| is_primary | bool | Yes | Default false |
| status | string | Yes | active/inactive |
| created_at | DateTime | Yes | Auto |
| updated_at | DateTime | Yes | Auto |

### 2.3 Opportunity

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | UUID |
| customer_id | string | Yes | FK to Customer |
| name | string | Yes | Opportunity name |
| amount | float | Yes | Deal value |
| probability | int | Yes | 0-100% |
| stage | string | Yes | prospecting/qualification/etc. |
| expected_close_date | Date | No | Expected close date |
| lead_source | string | No | Source of lead |
| campaign_id | string | No | FK to Campaign |
| assigned_to | string | Yes | User ID |
| closed_date | Date | No | Actual close date |
| closed_reason | string | No | won/lost reason |
| created_at | DateTime | Yes | Auto |
| updated_at | DateTime | Yes | Auto |

### 2.4 Communication

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | string | Yes | UUID |
| customer_id | string | Yes | FK to Customer |
| contact_id | string | No | FK to Contact |
| type | string | Yes | call/meeting/email/sms/note/letter |
| subject | string | Yes | Subject |
| description | text | No | Content |
| direction | string | Yes | inbound/outbound |
| outcome | string | No | Result of communication |
| opportunity_id | string | No | FK to Opportunity |
| user_id | string | Yes | User who logged |
| occurred_at | DateTime | Yes | When it happened |
| created_at | DateTime | Yes | Auto |

## 3. Functional Requirements

### FR-CRM-001: Customer Management
**Requirement**: System shall allow CRUD operations for customers.

**Features**:
- Create customer with extended CRM fields
- Edit customer details
- Deactivate/reactivate customer
- Link to FA debtor number
- Customer type and segment assignment
- Territory assignment
- Account manager assignment

### FR-CRM-002: Contact Management
**Requirement**: System shall support multiple contacts per customer.

**Features**:
- Add/edit/delete contacts
- Set primary contact
- Contact role assignment
- Contact history tracking
- Bulk contact import
- Contact deduplication

### FR-CRM-003: Opportunity Pipeline
**Requirement**: System shall manage sales opportunities through stages.

**Stages**:
- prospecting → qualification → needs_analysis → value_proposition → decision → proposal → negotiation → closed_won/closed_lost

**Features**:
- Create opportunity linked to customer
- Track amount, probability, close date
- Move through pipeline stages
- Win/loss tracking with reasons
- Automatic project creation on win (ksf_ProjectManagement)

### FR-CRM-004: Activity Logging
**Requirement**: System shall log all customer communications.

**Features**:
- Log calls, meetings, emails, SMS, notes
- Link to contact and opportunity
- Track outcome
- Timeline view per customer
- Filter by type, date, user

### FR-CRM-005: Customer Segmentation
**Requirement**: System shall support customer segmentation.

**Features**:
- Define segments (Enterprise, SMB, Startup, etc.)
- Assign customers to segments
- Query by segment
- Segment-based marketing (ksf_EmailManager)

### FR-CRM-006: Follow-Up Reminders
**Requirement**: System shall track and remind follow-ups.

**Features**:
- Set next follow-up date
- Cron-based reminder notifications
- Auto-create calendar events
- Escalation if overdue

### FR-CRM-007: Document Attachment
**Requirement**: System shall attach documents to customers.

**Features**:
- Upload files to customer record
- Link from ksf_Documents
- Version tracking
- Access control

### FR-CRM-008: Integration Events (PSR-14)

| Event | Trigger |
|-------|---------|
| `customer.created` | New customer |
| `customer.updated` | Customer updated |
| `opportunity.created` | New opportunity |
| `opportunity.stage_changed` | Stage progression |
| `opportunity.won` | Closed won |
| `opportunity.lost` | Closed lost |
| `communication.logged` | New communication |

## 4. Composer Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| ksfraser/exceptions | ^1.2 | Exception hierarchy |
| ksfraser/traits | ^1.0 | Trait library |
| psr/event-dispatcher | ^2.0 | PSR-14 events |

## 5. Exceptions

| Exception | Extends | Description |
|-----------|---------|-------------|
| `CRMException` | `RuntimeException` | Base CRM exception |
| `CRMCustomerNotFoundException` | `CRMException` | Customer not found |
| `CRMContactNotFoundException` | `CRMException` | Contact not found |
| `CRMOpportunityNotFoundException` | `CRMException` | Opportunity not found |
| `CRMValidationException` | `CRMException` | Validation errors |

---

*Document Version: 1.1.0*
*Last Updated: 2026-05-11*