# FA_CRM Functional Requirements

## Document Information
- **Module**: FA_CRM (Customer Relationship Management)
- **Version**: 1.0.0
- **Date**: 2024-04-26
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Overview

### 1.1 Purpose
This document defines the functional requirements for the FA_CRM module, which extends FrontAccounting's basic customer management into a comprehensive CRM system.

### 1.2 Scope
The CRM module provides:
- Enhanced customer profiles with industry, territory, and segmentation
- Multi-contact management per customer
- Sales opportunity pipeline tracking
- Communication logging with follow-up management
- Email import and calendar integration
- Analytics and reporting

## 2. Customer Management

### 2.1 Enhanced Customer Profiles (FR-CM-001)
**Requirement**: The system shall allow users to create and manage enhanced customer profiles with additional CRM-specific fields.

**Fields**:
- `customer_type_id` - Reference to customer type
- `customer_segment_id` - Reference to customer segment
- `territory_id` - Sales territory assignment
- `customer_since` - Date customer relationship started
- `website` - Customer website URL
- `industry` - Industry classification
- `employee_count` - Number of employees
- `annual_revenue` - Annual revenue
- `parent_company` - Parent/holding company
- `latitude/longitude` - Geographic coordinates
- `edi_enabled` - EDI capability flag
- `marketing_opt_out` - Marketing opt-out flag
- `preferred_contact_method` - Preferred communication method
- `last_contact_date` - Last contact timestamp
- `next_followup_date` - Next scheduled follow-up
- `account_manager` - Assigned account manager
- `credit_rating` - Credit rating (excellent/good/fair/poor)
- `payment_reliability` - Payment reliability percentage

**Priority**: High

### 2.2 Customer Types Management (FR-CM-002)
**Requirement**: The system shall allow administrators to define and manage customer types.

**Features**:
- Create customer types with name and description
- Edit existing customer types
- Delete customer types (with validation)
- List active customer types
- Default types: Prospect, Active, Inactive, VIP, Partner

**Priority**: High

### 2.3 Customer Segmentation (FR-CM-003)
**Requirement**: The system shall support customer segmentation for targeted marketing.

**Features**:
- Create segments with name and criteria
- Assign customers to segments
- Query customers by segment
- Default segments: Enterprise, SMB, Startup, Government

**Priority**: Medium

### 2.4 Territory Management (FR-CM-004)
**Requirement**: The system shall support sales territory management.

**Features**:
- Create territories with name, description, region
- Assign territory to customers
- List territories by region
- Default territories: North, South, East, West, Central

**Priority**: Medium

## 3. Contact Management

### 3.1 Multi-Contact Support (FR-CT-001)
**Requirement**: The system shall allow multiple contacts per customer.

**Fields**:
- `debtor_no` - Customer reference
- `contact_role_id` - Role reference
- `first_name` - Contact first name
- `last_name` - Contact last name
- `title` - Job title
- `department` - Department
- `phone` - Phone number
- `mobile` - Mobile number
- `email` - Email address
- `address` - Mailing address
- `notes` - Contact notes
- `is_primary` - Primary contact flag
- `inactive` - Active/inactive flag

**Priority**: High

### 3.2 Contact Roles (FR-CT-002)
**Requirement**: The system shall support contact roles for better organization.

**Default Roles**:
- Decision Maker
- Technical Contact
- Billing Contact
- Primary Contact
- Secondary Contact

**Priority**: Medium

### 3.3 Primary Contact Designation (FR-CT-003)
**Requirement**: The system shall support designation of a primary contact per customer.

**Behavior**:
- Only one primary contact per customer
- Primary contact shown in communications
- Primary contact receives important notifications

**Priority**: High

## 4. Sales Pipeline & Opportunities

### 4.1 Opportunity Management (FR-OP-001)
**Requirement**: The system shall allow users to create and manage sales opportunities.

**Fields**:
- `opportunity_name` - Opportunity name
- `debtor_no` - Customer reference
- `contact_id` - Associated contact
- `sales_person` - Assigned salesperson
- `opportunity_type` - Type (new_business, existing_business, renewal, expansion)
- `status` - Status (prospecting, qualified, proposal, negotiation, closed_won, closed_lost)
- `stage` - Pipeline stage
- `source` - Lead source
- `estimated_value` - Estimated deal value
- `probability` - Win probability percentage
- `expected_close_date` - Expected close date
- `actual_close_date` - Actual close date
- `lost_reason` - Reason if lost
- `won_notes` - Notes when won
- `notes` - General notes
- `assigned_to` - User assignment

**Priority**: High

### 4.2 Pipeline Stages (FR-OP-002)
**Requirement**: The system shall support configurable pipeline stages.

**Default Stages**:
1. Prospecting
2. Qualification
3. Discovery
4. Proposal
5. Negotiation
6. Closed Won
7. Closed Lost

**Priority**: High

### 4.3 Opportunity Valuation (FR-OP-003)
**Requirement**: The system shall calculate weighted pipeline value.

**Formula**: `Weighted Value = Estimated Value × (Probability / 100)`

**Priority**: Medium

## 5. Communication Tracking

### 5.1 Communication Logging (FR-CM-001)
**Requirement**: The system shall log all customer communications.

**Fields**:
- `debtor_no` - Customer reference
- `contact_id` - Contact reference
- `opportunity_id` - Associated opportunity
- `communication_type` - Type (phone, email, meeting, note, SMS, letter)
- `direction` - Direction (inbound, outbound, internal)
- `subject` - Communication subject
- `message` - Communication content
- `email_from` - Email sender
- `email_to` - Email recipient
- `phone_number` - Phone number
- `duration_minutes` - Call duration
- `status` - Status (scheduled, completed, cancelled, failed)
- `scheduled_date` - Scheduled date/time
- `completed_date` - Completed date/time
- `assigned_to` - Assigned user
- `priority` - Priority (low, medium, high, urgent)
- `follow_up_required` - Follow-up needed flag
- `follow_up_date` - Follow-up date
- `notes` - Additional notes
- `email_message_id` - Email message ID
- `attachment_path` - File attachment path
- `created_by` - User who logged

**Priority**: High

### 5.2 Follow-Up Management (FR-CM-002)
**Requirement**: The system shall track and remind users of follow-up activities.

**Features**:
- Mark communications requiring follow-up
- Set follow-up date
- List pending follow-ups
- Follow-up notifications

**Priority**: High

### 5.3 Communication Types (FR-CM-003)
**Requirement**: The system shall support multiple communication types.

**Types**:
- Phone Call
- Email
- Meeting
- Note
- SMS
- Letter

**Priority**: High

## 6. Email Integration

### 6.1 Email Account Management (FR-EM-001)
**Requirement**: The system shall allow configuration of email accounts for import.

**Fields**:
- `account_name` - Account name
- `email_address` - Email address
- `smtp_host` - SMTP server hostname
- `smtp_port` - SMTP port
- `smtp_username` - SMTP username
- `smtp_password` - SMTP password
- `smtp_encryption` - Encryption type (ssl, tls, none)
- `imap_host` - IMAP server hostname
- `imap_port` - IMAP port
- `imap_username` - IMAP username
- `imap_password` - IMAP password
- `imap_encryption` - IMAP encryption
- `auto_import` - Auto-import enabled flag
- `import_frequency_minutes` - Import frequency
- `last_sync` - Last sync timestamp

**Priority**: High

### 6.2 Email Import (FR-EM-002)
**Requirement**: The system shall import emails from configured IMAP accounts.

**Features**:
- Connect to IMAP server
- Fetch emails since last sync
- Match emails to contacts by address
- Create communication records
- Handle attachments

**Priority**: High

### 6.3 ICS Calendar Import (FR-EM-003)
**Requirement**: The system shall process ICS attachments from imported emails.

**Features**:
- Detect ICS attachments
- Parse ICS events
- Create/update meetings
- Add attendees from ICS

**Priority**: Medium

## 7. Meeting & Calendar

### 7.1 Meeting Management (FR-MT-001)
**Requirement**: The system shall manage customer meetings and appointments.

**Fields**:
- `meeting_name` - Meeting title
- `meeting_type` - Type (meeting, call, presentation, training, other)
- `description` - Meeting description
- `start_date` - Start date/time
- `end_date` - End date/time
- `duration_minutes` - Duration
- `time_zone` - Timezone
- `location_type` - Location type (physical, virtual, phone)
- `room_id` - Meeting room
- `custom_location` - Custom location
- `meeting_url` - Virtual meeting URL
- `conference_url` - Conference URL
- `dial_in_number` - Dial-in number
- `access_code` - Access code
- `host_pin` - Host PIN
- `debtor_no` - Customer reference
- `contact_id` - Contact reference
- `opportunity_id` - Opportunity reference
- `status` - Status (planned, confirmed, in_progress, completed, cancelled, postponed)
- `priority` - Priority
- `assigned_to` - Assigned user
- `created_by` - Created by user
- `ics_uid` - ICS unique ID
- `external_id` - External ID

**Priority**: High

### 7.2 Meeting Rooms (FR-MT-002)
**Requirement**: The system shall support meeting room booking.

**Features**:
- Create physical/virtual rooms
- Track room capacity
- View room availability
- Book rooms for meetings

**Priority**: Medium

### 7.3 Meeting Attendees (FR-MT-003)
**Requirement**: The system shall track meeting attendees.

**Attendee Types**:
- Employee
- Contact
- External

**Attendee Roles**:
- Organizer
- Required
- Optional
- Resource

**Priority**: Medium

## 8. Lead Management

### 8.1 Lead Capture (FR-LD-001)
**Requirement**: The system shall capture leads from web forms.

**Features**:
- Web-to-lead form processing
- Lead creation from form data
- Automatic assignment
- Lead notification

**Priority**: High

### 8.2 Lead Conversion (FR-LD-002)
**Requirement**: The system shall convert leads to customers.

**Features**:
- Convert lead to customer
- Migrate contact information
- Transfer communication history
- Associate opportunities

**Priority**: High

## 9. Analytics & Reporting

### 9.1 Customer Analytics (FR-AN-001)
**Requirement**: The system shall calculate customer analytics.

**Metrics**:
- `total_sales` - Total sales amount
- `total_payments` - Total payments received
- `outstanding_balance` - Outstanding invoice balance
- `payment_days_avg` - Average payment days
- `customer_lifetime_value` - Customer lifetime value
- `last_communication_date` - Last communication date
- `communication_count` - Total communication count

**Priority**: High

### 9.2 Pipeline Analytics (FR-AN-002)
**Requirement**: The system shall provide pipeline analytics.

**Metrics**:
- Total pipeline value
- Weighted pipeline value
- Opportunities by stage
- Win rate
- Average cycle time

**Priority**: Medium

### 9.3 Territory Analytics (FR-AN-003)
**Requirement**: The system shall provide territory performance reports.

**Metrics**:
- Sales by territory
- Customers by territory
- Pipeline by territory
- Win rate by territory

**Priority**: Medium

## 10. Activity Logging

### 10.1 Audit Trail (FR-AL-001)
**Requirement**: The system shall log all CRM activities for audit purposes.

**Logged Events**:
- Customer created/updated/deleted
- Contact created/updated/deleted
- Opportunity created/updated/deleted
- Communication logged
- Note added

**Priority**: High

## 11. Integration Features

### 11.1 Event System (FR-IN-001)
**Requirement**: The system shall dispatch events for cross-module integration.

**Events**:
- CRM customer events (created, updated, deleted)
- Contact events
- Opportunity events
- Communication events

**Priority**: High

### 11.2 EDI Configuration (FR-IN-002)
**Requirement**: The system shall support EDI configuration for B2B customers.

**Features**:
- EDI type configuration
- EDI code assignment
- FTP credentials
- Email notification settings

**Priority**: Medium

## 12. Permissions

### 12.1 Access Control (FR-AC-001)
**Requirement**: The system shall enforce role-based access control.

**Permission Constants**:
- `CRM_VIEW_CUSTOMER` - View customer data
- `CRM_MANAGE_CUSTOMER` - Manage customer data
- `CRM_VIEW_QUALIFY` - View opportunities
- `CRM_MANAGE_QUALIFY` - Manage opportunities
- `CRM_VIEW_COMMUNICATIONS` - View communications
- `CRM_MANAGE_COMMUNICATIONS` - Manage communications
- `CRM_VIEW_CALENDAR` - View calendar
- `CRM_MANAGE_CALENDAR` - Manage calendar
- `CRM_VIEW_ANALYTICS` - View analytics
- `CRM_ADMIN` - Full administrative access

**Priority**: High

## 13. Non-Functional Requirements

### 13.1 Performance
- Page load time < 3 seconds
- Database queries optimized with indexes
- Efficient pagination for large datasets

### 13.2 Security
- SQL injection prevention via prepared statements
- XSS prevention via output escaping
- CSRF protection on forms
- Secure password storage

### 13.3 Compatibility
- FrontAccounting 2.4.0+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.0+

### 13.4 Maintainability
- Modular code structure
- Clear separation of concerns
- Database abstraction layer
- Comprehensive comments

## 14. Appendix: Default Values

### Customer Types
| ID | Name | Description |
|----|------|-------------|
| 1 | Prospect | Potential new customer |
| 2 | Active | Current active customer |
| 3 | Inactive | Former customer |
| 4 | VIP | High-value customer |
| 5 | Partner | Business partner |

### Territories
| ID | Name | Description | Region |
|----|------|-------------|--------|
| 1 | North | Northern region | North |
| 2 | South | Southern region | South |
| 3 | East | Eastern region | East |
| 4 | West | Western region | West |
| 5 | Central | Central region | Central |

### Communication Types
- call (Phone Call)
- meeting (Meeting)
- email (Email)
- sms (SMS)
- note (Note)
- letter (Letter)

### Opportunity Stages
- prospecting
- qualification
- discovery
- proposal
- negotiation
- closed_won
- closed_lost

---
*Document Version: 1.0.0*
*Last Updated: 2024-04-26*
