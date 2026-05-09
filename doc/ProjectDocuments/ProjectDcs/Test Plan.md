# FA_CRM Test Plan

## Document Information
- **Module**: FA_CRM (Customer Relationship Management)
- **Version**: 1.0.0
- **Date**: 2024-04-26
- **Status**: Implemented
- **Author**: KSFII Development Team

## 1. Introduction

### 1.1 Purpose
This test plan defines the testing strategy and approach for the FA_CRM module, ensuring all functional requirements are met and the module functions correctly within the FrontAccounting framework.

### 1.2 Scope
- Unit testing of core functions
- Integration testing with FA framework
- UI component testing
- Email import service testing
- Database operation testing

### 1.3 Test Environment
- **PHP Version**: 8.0+
- **Database**: MySQL 5.7+ / MariaDB 10.0+
- **FA Version**: 2.4.0+
- **Testing Framework**: PHPUnit

## 2. Testing Strategy

### 2.1 Test Levels

#### Unit Testing
Testing individual functions and methods in isolation.

#### Integration Testing
Testing the module's interaction with FA core components.

#### System Testing
End-to-end testing of complete workflows.

### 2.2 Test Types

| Type | Description | Coverage |
|------|-------------|----------|
| Functional | Verify features work as specified | 100% |
| Regression | Ensure no existing features broken | 100% |
| Performance | Verify acceptable response times | Selected |
| Security | Verify input validation | Critical paths |

## 3. Test Cases by Module

### 3.1 Customer Management (TC-CM)

#### TC-CM-001: Create Customer CRM Profile
**Preconditions**: 
- FA customer exists
- User has CRM_MANAGE_CUSTOMER permission

**Test Steps**:
1. Create new FA customer
2. Verify CRM profile auto-created
3. Query crm_customers table

**Expected Result**: CRM profile created with default values

**Priority**: High

#### TC-CM-002: Update Customer CRM Data
**Preconditions**: 
- Customer with CRM profile exists

**Test Steps**:
1. Update customer_type_id
2. Update territory_id
3. Update credit_rating

**Expected Result**: All fields updated correctly

**Priority**: High

#### TC-CM-003: Customer Type CRUD
**Preconditions**: User has CRM_ADMIN permission

**Test Steps**:
1. Create new customer type
2. List customer types
3. Update customer type
4. Delete customer type

**Expected Result**: All operations succeed

**Priority**: High

#### TC-CM-004: Territory CRUD
**Preconditions**: User has CRM_ADMIN permission

**Test Steps**:
1. Create new territory
2. List territories
3. Update territory
4. Delete territory

**Expected Result**: All operations succeed

**Priority**: Medium

#### TC-CM-005: Enhanced Customer Search
**Preconditions**: Multiple customers with different types/territories

**Test Steps**:
1. Search by customer type
2. Search by territory
3. Search by credit rating

**Expected Result**: Correctly filtered results

**Priority**: Medium

### 3.2 Contact Management (TC-CT)

#### TC-CT-001: Add Customer Contact
**Preconditions**: Customer exists

**Test Steps**:
1. Add contact with all fields
2. Verify contact created
3. Check debtor_no foreign key

**Expected Result**: Contact created and linked

**Priority**: High

#### TC-CT-002: Primary Contact Designation
**Preconditions**: Multiple contacts exist

**Test Steps**:
1. Set first contact as primary
2. Add second contact
3. Set second as primary first should auto-unset

**Expected Result**: Only one primary at a time

**Priority**: High

#### TC-CT-003: Contact Role Assignment
**Preconditions**: Contact roles exist

**Test Steps**:
1. Assign contact role to contact
2. Query contact with role

**Expected Result**: Role correctly assigned

**Priority**: Medium

### 3.3 Opportunity Management (TC-OP)

#### TC-OP-001: Create Opportunity
**Preconditions**: Customer exists

**Test Steps**:
1. Create opportunity with all fields
2. Verify opportunity created
3. Check estimated_value and probability

**Expected Result**: Opportunity created with calculated weighted value

**Priority**: High

#### TC-OP-002: Pipeline Stage Update
**Preconditions**: Opportunity exists

**Test Steps**:
1. Update stage from qualification to proposal
2. Update stage to negotiation
3. Update stage to closed_won

**Expected Result**: Stage progression tracked

**Priority**: High

#### TC-OP-003: Pipeline Analytics
**Preconditions**: Multiple opportunities with different stages

**Test Steps**:
1. Query pipeline by stage
2. Calculate total value
3. Calculate weighted value

**Expected Result**: Correct totals calculated

**Priority**: Medium

### 3.4 Communication Tracking (TC-COM)

#### TC-COM-001: Log Phone Communication
**Preconditions**: Customer and contact exist

**Test Steps**:
1. Log phone call with duration
2. Log follow-up date
3. Query communications

**Expected Result**: Communication logged with all details

**Priority**: High

#### TC-COM-002: Log Email Communication
**Preconditions**: Customer and contact exist

**Test Steps**:
1. Log email communication
2. Include email_from and email_to
3. Set status to completed

**Expected Result**: Email communication stored

**Priority**: High

#### TC-COM-003: Follow-Up Reminder
**Preconditions**: Communication with follow-up exists

**Test Steps**:
1. Set follow_up_required = 1
2. Set follow_up_date in past
3. Query pending follow-ups

**Expected Result**: Follow-up appears in pending list

**Priority**: High

### 3.5 Email Import (TC-EM)

#### TC-EM-001: Email Account Configuration
**Preconditions**: IMAP server available

**Test Steps**:
1. Add email account with credentials
2. Test IMAP connection
3. Fetch email account

**Expected Result**: Account configured and connected

**Priority**: High

#### TC-EM-002: Email Import Process
**Preconditions**: 
- Email account configured
- Emails on server

**Test Steps**:
1. Run email import
2. Verify new communications created
3. Check contact matching

**Expected Result**: Emails imported and linked to contacts

**Priority**: High

#### TC-EM-003: ICS Meeting Import
**Preconditions**: 
- Email with ICS attachment exists
- Contact exists in system

**Test Steps**:
1. Import email with ICS
2. Verify meeting created
3. Check attendee added

**Expected Result**: Meeting created from ICS

**Priority**: Medium

### 3.6 Meeting Management (TC-MT)

#### TC-MT-001: Create Physical Meeting
**Preconditions**: 
- Customer exists
- Meeting room configured

**Test Steps**:
1. Create meeting with room
2. Set start/end time
3. Add customer and contact

**Expected Result**: Meeting created with room

**Priority**: Medium

#### TC-MT-002: Create Virtual Meeting
**Preconditions**: Customer exists

**Test Steps**:
1. Create meeting with location_type = virtual
2. Include meeting_url
3. Set conference_url

**Expected Result**: Virtual meeting created

**Priority**: Medium

#### TC-MT-003: Meeting Attendees
**Preconditions**: Meeting exists

**Test Steps**:
1. Add employee attendee
2. Add contact attendee
3. Add external attendee

**Expected Result**: All attendee types added

**Priority**: Medium

### 3.7 Lead Management (TC-LD)

#### TC-LD-001: Web-to-Lead Form
**Preconditions**: None

**Test Steps**:
1. Submit web form with lead data
2. Verify lead created
3. Check assignment

**Expected Result**: Lead captured from web form

**Priority**: High

#### TC-LD-002: Lead Conversion
**Preconditions**: Lead exists

**Test Steps**:
1. Convert lead to customer
2. Verify FA customer created
3. Verify CRM profile created

**Expected Result**: Lead converted to customer

**Priority**: High

### 3.8 Analytics (TC-AN)

#### TC-AN-001: Customer Lifetime Value
**Preconditions**: Customer with transactions exists

**Test Steps**:
1. Calculate customer analytics
2. Verify total_sales calculation
3. Verify LTV calculation

**Expected Result**: Correct analytics calculated

**Priority**: Medium

#### TC-AN-002: Pipeline Summary
**Preconditions**: Multiple opportunities exist

**Test Steps**:
1. Get pipeline summary
2. Calculate by stage
3. Verify weighted values

**Expected Result**: Correct pipeline metrics

**Priority**: Medium

### 3.9 Activity Logging (TC-AL)

#### TC-AL-001: Activity Log Creation
**Preconditions**: User actions performed

**Test Steps**:
1. Create contact
2. Update customer
3. Log communication

**Expected Result**: Activities logged with details

**Priority**: High

### 3.10 Permissions (TC-AC)

#### TC-AC-001: Permission Enforcement
**Preconditions**: Multiple users with different roles

**Test Steps**:
1. Test with CRM_VIEW_CUSTOMER only
2. Test with CRM_MANAGE_CUSTOMER

**Expected Result**: Access correctly enforced

**Priority**: High

## 4. Performance Tests

### 4.1 Large Dataset Tests
- Test with 10,000 customers
- Test with 50,000 contacts
- Test with 100,000 communications

### 4.2 Response Time Requirements
- Dashboard load: < 3 seconds
- Customer list (100 records): < 1 second
- Search results: < 2 seconds

## 5. Security Tests

### 5.1 Input Validation Tests
- SQL injection attempts
- XSS in description fields
- Invalid email formats

### 5.2 Access Control Tests
- Unauthorized access attempts
- Cross-user data access
- Privilege escalation

## 6. Test Data Management

### 6.1 Test Data Requirements
- Sample customers (minimum 10)
- Sample contacts (minimum 20)
- Sample opportunities (minimum 10)
- Sample communications (minimum 50)

### 6.2 Test Data Cleanup
- Use database transactions for rollback
- Clean up after test suites
- Reset auto-increment counters

## 7. Test Execution

### 7.1 Test Run Matrix

| Environment | Browser | FA Version | PHP Version |
|-------------|---------|------------|--------------|
| Local | Chrome | 2.4.x | 8.1 |
| Dev | Firefox | 2.4.x | 8.0 |
| CI | Headless | 2.4.x | 8.1 |

### 7.2 Test Schedule
- Unit tests: Every commit
- Integration tests: Daily
- Full regression: Before release

### 7.3 Pass/Fail Criteria
- Unit tests: 100% pass required
- Integration tests: 95% pass required
- Critical functional tests: 100% pass required

## 8. Defect Reporting

### 8.1 Severity Levels
- **Critical**: System crash, data loss
- **High**: Core feature not working
- **Medium**: Feature partially working
- **Low**: cosmetic issue

### 8.2 Priority Levels
- **P0**: Must fix before release
- **P1**: Should fix before release
- **P2**: Can fix in next release
- **P3**: Backlog

## 9. Risk Assessment

### 9.1 High-Risk Areas
| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Email import failures | Medium | High | Extensive test cases |
| Performance with large data | High | Medium | Query optimization |
| FA version compatibility | Low | High | Version checking |

### 9.2 Mitigation Strategies
- Comprehensive test coverage
- Regular compatibility testing
- Performance profiling

## 10. Test Deliverables

- [x] Test Plan Document
- [x] Test Cases (this document)
- [x] Test Scripts (tests/ directory)
- [x] Test Data Setup
- [x] Test Results Reports
- [x] Defect Reports

---
*Document Version: 1.0.0*
*Last Updated: 2024-04-26*
