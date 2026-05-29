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
| ksfraser/rbac | ^0.1 | RBAC/ABAC access control |
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

## 6. RBAC Security Requirements

### FR-CRM-009: RBAC Integration
**Requirement**: All CRM record queries MUST be filtered through a JOIN against `0_rbac_record_access` and `0_rbac_team_members`. No record is returned without a matching RBAC row.

**Verification**:
- `SELECT` queries on customers, contacts, opportunities, and communications include the RBAC JOIN
- Removing a user from a team immediately removes record visibility
- No secondary application-layer permission check exists for reads

### FR-CRM-010: Teams-Only Access
**Requirement**: All access grants target teams, never individual users. Every user has an `{userId}_individual` team for personal grants. No direct user-to-record `0_rbac_record_access` rows exist.

**Verification**:
- All `team_id` values in `0_rbac_record_access` reference `0_rbac_teams`
- Individual access implemented via `{userId}_individual` team
- API rejects any grant that does not specify a team ID

### FR-CRM-011: DTO Projections
**Requirement**: All CRM entities MUST define named projection constants (`PROJECTION_PUBLIC`, `PROJECTION_ACCOUNT`, `PROJECTION_FULL`). Data access objects MUST filter fields based on the projection granted in `0_rbac_record_access`.

**Projections per entity**:

| Entity | PUBLIC | ACCOUNT | FULL |
|--------|--------|---------|------|
| Customer | name, phone, email, status | — | — |
| Customer (ACCOUNT) | — | + credit_rating, ar_balance, segment | — |
| Customer (FULL) | — | — | all fields |
| Contact | name, email, phone | — | all fields incl. address, PII |
| Opportunity | name, stage, probability, amount | — | all fields incl. notes, internal comments |
| Communication | subject, type, date, direction | — | all fields incl. description, outcome |

### FR-CRM-012: Soft Delete
**Requirement**: The system MUST NOT hard-delete CRM records. All deletes set `deleted=1`, `deleted_by`, `deleted_at`. Hard delete is a super-admin-only operation.

**Verification**:
- All entity `DELETE` operations execute `UPDATE ... SET deleted=1`
- Standard queries include `WHERE deleted=0`
- Users with `can_view_deleted` may query soft-deleted records
- Hard delete requires `can_hard_delete` type-level capability

### FR-CRM-013: Default Deny
**Requirement**: Absence of an RBAC grant is equivalent to deny. No record is visible to a user who has no matching row in `0_rbac_record_access` for that record through any of their teams.

**Verification**:
- User with no team membership sees empty record lists
- Removing the last team grant for a record makes it disappear
- No fallback "allow all" mode exists

### FR-CRM-014: Type-Level Permissions
**Requirement**: Type-level capabilities (`can_create`, `can_hard_delete`, `can_view_deleted`) are managed through `0_rbac_role_permissions`, not through individual record xref rows.

**Verification**:
- `can_create` checked before record insertion
- `can_hard_delete` checked before hard DELETE execution
- `can_view_deleted` checked before including soft-deleted records in results

### FR-CRM-015: Audit
**Requirement**: All RBAC permission grants, revocations, elevation events, and deny overrides MUST be logged to the audit trail via ksfraser/rbac's `AuditLoggerInterface`.

**Verification**:
- Granting a team access to a record generates an audit entry
- Revoking (setting `inactive=1`) generates an audit entry
- Switch-role elevation generates an audit entry
- Deny override application generates an audit entry

---

*Document Version: 1.1.0*
*Last Updated: 2026-05-24*