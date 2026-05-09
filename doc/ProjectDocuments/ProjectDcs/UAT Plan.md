# FA_CRM UAT Plan

## Document Information
- **Module**: FA_CRM (Customer Relationship Management)
- **Version**: 1.0.0
- **Date**: 2024-04-26
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Introduction

### 1.1 Purpose
This UAT Plan defines the user acceptance test cases for the FA_CRM module. These tests verify that the module meets business requirements from an end-user perspective.

### 1.2 Scope
- Customer management features
- Contact management
- Opportunity pipeline
- Communication tracking
- Email import
- Meeting scheduling
- Lead conversion
- Analytics and reporting

### 1.3 Test Environment
- **Platform**: FrontAccounting 2.4.x
- **Browser**: Chrome/Firefox latest
- **PHP**: 8.0+
- **Database**: MySQL 5.7+

### 1.4 Stakeholders
- Sales Managers
- Account Managers
- CRM Administrators
- Customer Service Representatives

## 2. UAT Test Cases

### 2.1 Customer Management (UAT-CM)

#### UAT-CM-001: View Enhanced Customer Profile
**Objective**: Verify user can view enhanced customer information

**Test Scenario**:
1. Navigate to CRM Customers
2. Select a customer
3. View customer details

**Expected Result**: All CRM fields visible (type, territory, credit rating, industry, etc.)

**Acceptance Criteria**:
- [ ] Customer type displays correctly
- [ ] Territory displays correctly
- [ ] Industry displays correctly
- [ ] Account manager displays correctly
- [ ] Credit rating displays with formatting
- [ ] Website link is clickable

---

#### UAT-CM-002: Create Customer Type
**Objective**: Verify user can create new customer types

**Test Scenario**:
1. Navigate to Settings → Customer Types
2. Click Add New
3. Enter type name and description
4. Save

**Expected Result**: New customer type created and appears in list

**Acceptance Criteria**:
- [ ] Type saved to database
- [ ] Type appears in dropdown
- [ ] Type can be edited
- [ ] Type can be deleted (if not in use)

---

#### UAT-CM-003: Create Territory
**Objective**: Verify user can create sales territories

**Test Scenario**:
1. Navigate to Settings → Territories
2. Click Add New
3. Enter territory name, description, region
4. Save

**Expected Result**: New territory created

**Acceptance Criteria**:
- [ ] Territory saved to database
- [ ] Territory appears in customer dropdown
- [ ] Territory can be assigned to customers

---

#### UAT-CM-004: Edit Customer CRM Fields
**Objective**: Verify user can update customer CRM data

**Test Scenario**:
1. Open customer record
2. Click Edit CRM Fields
3. Update industry to "Manufacturing"
4. Update territory to "West"
5. Update credit rating to "Excellent"
6. Save

**Expected Result**: All fields updated correctly

**Acceptance Criteria**:
- [ ] Industry updates successfully
- [ ] Territory updates successfully
- [ ] Credit rating updates successfully
- [ ] Changes persist after reload

---

#### UAT-CM-005: Customer Analytics Display
**Objective**: Verify customer analytics show correctly

**Test Scenario**:
1. Open customer with transactions
2. View CRM section
3. Check analytics displayed

**Expected Result**: Analytics calculated correctly

**Acceptance Criteria**:
- [ ] Total sales shows correct amount
- [ ] Outstanding balance shows correct amount
- [ ] Lifetime value calculates correctly
- [ ] Payment reliability percentage shows

---

### 2.2 Contact Management (UAT-CT)

#### UAT-CT-001: Add Contact to Customer
**Objective**: Verify user can add contacts to customers

**Test Scenario**:
1. Open customer record
2. Navigate to Contacts section
3. Click Add Contact
4. Enter first name, last name, email, phone
5. Set as primary contact
6. Save

**Expected Result**: Contact created and linked to customer

**Acceptance Criteria**:
- [ ] Contact saved to database
- [ ] Contact linked to customer (debtor_no)
- [ ] Primary flag set correctly
- [ ] Contact appears in contact list

---

#### UAT-CT-002: Set Primary Contact
**Objective**: Verify only one primary contact allowed

**Test Scenario**:
1. Contact A is primary
2. Add Contact B
3. Set Contact B as primary

**Expected Result**: Contact A auto-demoted to non-primary

**Acceptance Criteria**:
- [ ] Only one contact shows as primary
- [ ] Previous primary shows as standard
- [ ] Change persists after reload

---

#### UAT-CT-003: Edit Contact
**Objective**: Verify user can update contact information

**Test Scenario**:
1. Open contact record
2. Edit phone number
3. Update email address
4. Save

**Expected Result**: Contact updated successfully

**Acceptance Criteria**:
- [ ] Phone updated
- [ ] Email updated
- [ ] Changes persist after reload

---

#### UAT-CT-004: Delete Contact
**Objective**: Verify user can delete contacts

**Test Scenario**:
1. Open contact record
2. Click Delete
3. Confirm deletion

**Expected Result**: Contact removed from system

**Acceptance Criteria**:
- [ ] Contact removed from list
- [ ] Database record deleted
- [ ] Associated communications remain

---

### 2.3 Opportunity Management (UAT-OP)

#### UAT-OP-001: Create Sales Opportunity
**Objective**: Verify user can create sales opportunities

**Test Scenario**:
1. Navigate to Opportunities
2. Click Add New
3. Enter opportunity name
4. Select customer
5. Select contact
6. Enter estimated value: 50000
7. Enter probability: 75%
8. Set expected close date
9. Save

**Expected Result**: Opportunity created with weighted value

**Acceptance Criteria**:
- [ ] Opportunity saved
- [ ] Customer linked
- [ ] Contact linked
- [ ] Weighted value calculates (50000 × 0.75 = 37500)

---

#### UAT-OP-002: Update Opportunity Stage
**Objective**: Verify stage progression works

**Test Scenario**:
1. Open opportunity
2. Change stage from "Qualification" to "Proposal"
3. Save
4. Change to "Negotiation"
5. Save
6. Change to "Closed Won"
7. Enter actual close date and notes
8. Save

**Expected Result**: Full stage history tracked

**Acceptance Criteria**:
- [ ] Stage changes save correctly
- [ ] Actual close date saves
- [ ] Won notes save
- [ ] Status reflects closed_won

---

#### UAT-OP-003: View Pipeline Summary
**Objective**: Verify pipeline dashboard shows correct metrics

**Test Scenario**:
1. Navigate to Dashboard
2. View Pipeline section

**Expected Result**: Pipeline values calculated correctly

**Acceptance Criteria**:
- [ ] Total pipeline value displays
- [ ] Weighted value displays
- [ ] Opportunities by stage shows correctly
- [ ] Values calculate correctly

---

#### UAT-OP-004: Delete Opportunity
**Objective**: Verify user can delete opportunities

**Test Scenario**:
1. Open opportunity
2. Click Delete
3. Confirm

**Expected Result**: Opportunity removed

**Acceptance Criteria**:
- [ ] Opportunity removed from list
- [ ] Database record deleted
- [ ] Associated communications remain

---

### 2.4 Communication Tracking (UAT-COM)

#### UAT-COM-001: Log Phone Call
**Objective**: Verify user can log phone communications

**Test Scenario**:
1. Navigate to customer communications
2. Click Log Communication
3. Select type: Phone Call
4. Select contact
5. Enter subject
6. Enter duration: 15 minutes
7. Set direction: Outbound
8. Save

**Expected Result**: Communication logged

**Acceptance Criteria**:
- [ ] Communication saved
- [ ] Contact linked
- [ ] Duration stored
- [ ] Appears in communication list

---

#### UAT-COM-002: Schedule Follow-Up
**Objective**: Verify follow-up scheduling works

**Test Scenario**:
1. Log communication
2. Check "Follow-up Required"
3. Set follow-up date: tomorrow
4. Save
5. Navigate to follow-up list

**Expected Result**: Follow-up appears in pending list

**Acceptance Criteria**:
- [ ] Follow-up flag set
- [ ] Follow-up date saved
- [ ] Appears in follow-up dashboard
- [ ] Overdue follow-ups highlighted

---

#### UAT-COM-003: Log Email
**Objective**: Verify email logging

**Test Scenario**:
1. Click Log Communication
2. Select type: Email
3. Enter from/to addresses
4. Enter subject and message
5. Set status: Completed
6. Save

**Expected Result**: Email logged

**Acceptance Criteria**:
- [ ] Email_from saved
- [ ] Email_to saved
- [ ] Message body stored
- [ ] Status shows completed

---

#### UAT-COM-004: View Communication History
**Objective**: Verify communication history displays

**Test Scenario**:
1. Navigate to customer
2. View Communications section
3. Verify list displays

**Expected Result**: Communications list shows all

**Acceptance Criteria**:
- [ ] All communications show
- [ ] Sort by date descending
- [ ] Contact name shows
- [ ] Status shows color-coded

---

### 2.5 Email Import (UAT-EM)

#### UAT-EM-001: Configure Email Account
**Objective**: Verify user can configure email account

**Test Scenario**:
1. Navigate to Settings → Email Accounts
2. Click Add Account
3. Enter account name and email
4. Enter IMAP credentials
5. Set import frequency
6. Test connection
7. Save

**Expected Result**: Email account configured

**Acceptance Criteria**:
- [ ] Account saves
- [ ] Connection test passes
- [ ] Account appears in list

---

#### UAT-EM-002: Manual Email Import
**Objective**: Verify manual email import works

**Test Scenario**:
1. Configure email account
2. Click Import Now
3. Wait for import to complete
4. View imported emails

**Expected Result**: Emails imported as communications

**Acceptance Criteria**:
- [ ] Import completes without error
- [ ] New communications created
- [ ] Contacts matched by email

---

#### UAT-EM-003: ICS Meeting Creation
**Objective**: Verify ICS attachments create meetings

**Test Scenario**:
1. Send email with ICS to configured inbox
2. Run email import
3. Check meetings created

**Expected Result**: Meeting created from ICS

**Acceptance Criteria**:
- [ ] Meeting created
- [ ] Title from ICS (SUMMARY)
- [ ] Start time from ICS (DTSTART)
- [ ] Attendees from ICS (ATTENDEE)

---

### 2.6 Meeting Management (UAT-MT)

#### UAT-MT-001: Schedule Meeting
**Objective**: Verify user can schedule meetings

**Test Scenario**:
1. Navigate to Calendar
2. Click New Meeting
3. Enter meeting title
4. Select customer and contact
5. Set start and end time
6. Select location type: Physical
7. Select meeting room
8. Save

**Expected Result**: Meeting scheduled

**Acceptance Criteria**:
- [ ] Meeting saved
- [ ] Room booked
- [ ] Customer linked
- [ ] Appears in calendar

---

#### UAT-MT-002: Schedule Virtual Meeting
**Objective**: Verify virtual meeting scheduling

**Test Scenario**:
1. Create meeting with location: Virtual
2. Enter meeting URL
3. Enter conference URL
4. Save

**Expected Result**: Virtual meeting created

**Acceptance Criteria**:
- [ ] Meeting created
- [ ] Meeting URL stored
- [ ] Conference URL stored (if applicable)
- [ ] URLs are clickable

---

#### UAT-MT-003: Add Meeting Attendees
**Objective**: Verify attendee management

**Test Scenario**:
1. Open meeting
2. Click Add Attendee
3. Select attendee type: Contact
4. Choose contact
5. Set role: Required
6. Save

**Expected Result**: Attendee added

**Acceptance Criteria**:
- [ ] Attendee saved
- [ ] Type shows correctly
- [ ] Role shows correctly
- [ ] Response status can be tracked

---

#### UAT-MT-004: View Upcoming Meetings
**Objective**: Verify calendar displays meetings

**Test Scenario**:
1. Navigate to Calendar
2. View upcoming meetings

**Expected Result**: Calendar shows meetings

**Acceptance Criteria**:
- [ ] Meetings display on correct dates
- [ ] Click opens meeting details
- [ ] Past meetings shown differently

---

### 2.7 Lead Management (UAT-LD)

#### UAT-LD-001: Web-to-Lead Capture
**Objective**: Verify web form captures leads

**Test Scenario**:
1. Submit web form with lead data
2. Check lead created in system

**Expected Result**: Lead captured

**Acceptance Criteria**:
- [ ] Lead record created
- [ ] Name and email captured
- [ ] Lead status is "new"
- [ ] Notification sent (if configured)

---

#### UAT-LD-002: Lead Conversion
**Objective**: Verify lead conversion to customer

**Test Scenario**:
1. Open lead record
2. Click Convert to Customer
3. Confirm conversion
4. Check customer created

**Expected Result**: Lead converted to customer

**Acceptance Criteria**:
- [ ] FA customer created
- [ ] CRM profile created
- [ ] Contact migrated
- [ ] Lead marked as converted

---

### 2.8 Reports & Analytics (UAT-AN)

#### UAT-AN-001: Pipeline Report
**Objective**: Verify pipeline reporting

**Test Scenario**:
1. Navigate to Reports
2. Select Pipeline Report
3. View by stage
4. View by salesperson

**Expected Result**: Reports display correctly

**Acceptance Criteria**:
- [ ] Stage breakdown accurate
- [ ] Values calculate correctly
- [ ] Export works (if available)

---

#### UAT-AN-002: Customer Activity Report
**Objective**: Verify activity reporting

**Test Scenario**:
1. Navigate to Reports
2. Select Customer Activity
3. Select date range

**Expected Result**: Activity shows correctly

**Acceptance Criteria**:
- [ ] Communications counted
- [ ] Last contact date accurate
- [ ] Follow-ups tracked

---

### 2.9 Administration (UAT-AD)

#### UAT-AD-001: User Permissions
**Objective**: Verify permission assignment

**Test Scenario**:
1. As admin, navigate to user roles
2. Assign CRM permissions to user
3. Test access as that user

**Expected Result**: Permissions enforced

**Acceptance Criteria**:
- [ ] Menu items show based on permissions
- [ ] CRUD operations restricted
- [ ] Dashboard shows appropriate data

---

#### UAT-AD-002: Activity Log Viewing
**Objective**: Verify audit trail

**Test Scenario**:
1. Navigate to Settings → Activity Log
2. View recent activities

**Expected Result**: Activities logged

**Acceptance Criteria**:
- [ ] User actions logged
- [ ] Timestamps accurate
- [ ] Details show old/new values

---

### 2.10 Integration Tests (UAT-INT)

#### UAT-INT-001: FA Customer Sync
**Objective**: Verify CRM sync with FA customers

**Test Scenario**:
1. Create new FA customer
2. Check CRM profile auto-created

**Expected Result**: CRM profile created

**Acceptance Criteria**:
- [ ] CRM profile created automatically
- [ ] debtor_no matches FA customer

---

#### UAT-INT-002: Event Dispatching
**Objective**: Verify events fire for integration

**Test Scenario**:
1. Perform customer action
2. Check hooks triggered

**Expected Result**: Events dispatch correctly

**Acceptance Criteria**:
- [ ] Events fire on customer create
- [ ] Events fire on customer update
- [ ] External modules can listen

---

## 3. Sign-Off Criteria

### 3.1 Test Completion Metrics
- **Total UAT Test Cases**: 35+
- **Passed**: [ ]
- **Failed**: [ ]
- **Blocked**: [ ]
- **Pass Rate**: [ ]%

### 3.2 Sign-Off Requirements
All critical (P0) test cases must pass:
- [ ] Customer profile management
- [ ] Contact management  
- [ ] Opportunity pipeline
- [ ] Communication logging
- [ ] Permission enforcement

### 3.3 Sign-Off Table
| Test Area | Tester | Date | Result |
|----------|--------|------|--------|
| Customer Management | | | Pass/Fail |
| Contact Management | | | Pass/Fail |
| Opportunities | | | Pass/Fail |
| Communications | | | Pass/Fail |
| Email Import | | | Pass/Fail |
| Meetings | | | Pass/Fail |
| Reports | | | Pass/Fail |

---

## 4. Defect Reporting

### 4.1 Defect Categories
- **Critical**: System crash, data loss
- **Major**: Core feature not working
- **Minor**: Feature partially working
- **Cosmetic**: UI issue

### 4.2 Defect Report Template
```
ID: 
Test Case: 
Environment: 
Expected: 
Actual: 
Severity: 
Priority: 
Tester: 
Date: 
```

---

## 5. Success Criteria

### 5.1 Go/No-Go Decision
The module passes UAT when:
1. 100% critical test cases pass
2. 95% overall test cases pass
3. No Critical or Major defects open
4. Business sign-off obtained

### 5.2 Issue Resolution
- **Critical**: Must fix before release
- **Major**: Must fix before release
- **Minor**: Release OK with known issues
- **Cosmetic**: Can defer to next release

---
*Document Version: 1.0.0*
*Last Updated: 2024-04-26*
