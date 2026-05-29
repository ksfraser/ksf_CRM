# Use Cases - ksf_CRM

> **All use cases are subject to RBAC enforcement per ksfraser/rbac. Users must have appropriate team-membership grants in `0_rbac_record_access` for the relevant record types.**

## UC-CRM-001: Create New Customer
**Actor**: Sales Representative

**Preconditions**: User has CRM access

**Flow**:
1. Navigate to CRM > Customers > New
2. Search for existing contact (may already exist)
3. Enter customer details:
   - Company name
   - Customer type (Prospect, Active, VIP)
   - Industry, territory
   - Account manager assignment
   - Initial contact information
4. Save customer record
5. Add primary contact
6. Optionally create first opportunity

**Alternate Flow - Convert Lead**:
1. Lead qualification workflow triggers
2. Sales rep converts lead to customer
3. System creates customer, links contact
4. Opportunity created automatically

---

## UC-CRM-002: Log Customer Communication
**Actor**: Sales Rep, Account Manager, Support

**Trigger**: Communication with customer (call, email, meeting)

**Flow**:
1. Navigate to customer record
2. Click "Log Activity"
3. Select communication type:
   - Phone Call
   - Meeting
   - Email
   - Note
   - SMS
4. Enter details:
   - Date/time
   - Subject
   - Description
   - Outcome
   - Related opportunity (optional)
5. Attach documents (ksf_Documents)
6. Save
7. System creates calendar event if meeting (ksf_Calendar)
8. System logs for reporting

---

## UC-CRM-003: Manage Sales Opportunity
**Actor**: Sales Representative

**Flow**:
1. Navigate to customer > Opportunities
2. Create new opportunity:
   - Opportunity name
   - Amount, probability
   - Expected close date
   - Stage (Prospecting → Qualification → ... → Closed Won/Lost)
3. Link to products/services
4. Add activities (calls, emails, meetings)
5. Update stage as progresses
6. At close:
   - If Won → trigger project creation (ksf_ProjectManagement)
   - If Lost → log reason, archive

**Stage Progression**:
- prospecting → qualification → needs_analysis → value_proposition → decision → proposal → negotiation → closed_won/closed_lost

---

## UC-CRM-004: Customer Follow-Up Reminder
**Actor**: System, Sales Rep

**Trigger**: Next follow-up date reached

**Flow**:
1. System checks for follow-ups due (daily cron)
2. For each due follow-up:
   - Send notification to assigned rep
   - Create calendar event (ksf_Calendar)
   - Add to daily task list
3. Rep completes follow-up
4. Rep logs activity and sets next follow-up date
5. Repeat cycle

---

## UC-CRM-005: Customer Segment Assignment
**Actor**: Marketing, System

**Trigger**: Customer criteria match

**Flow**:
1. Marketing defines segment criteria:
   - Industry = 'Technology'
   - Revenue > 1M
   - Region = 'North'
2. System runs segmentation nightly
3. New matches auto-assigned to segment
4. Segment-based marketing campaigns triggered
5. Rep sees segment badge on customer view

---

## UC-CRM-006: Link Support Ticket to Customer
**Actor**: Support Agent, System

**Flow**:
1. Customer submits ticket (ksf_SupportTickets)
2. System links ticket to customer record
3. Support agent sees:
   - Customer profile
   - Recent communications
   - Open tickets
   - Lifetime value
4. Support agent logs resolution in customer timeline
5. Escalation notifies account manager if SLA breached

---

## UC-CRM-007: Convert Opportunity to Project
**Actor**: Sales Rep, System

**Trigger**: Opportunity stage = 'closed_won'

**Flow**:
1. Opportunity marked as won
2. System emits `opportunity.won` event
3. Workflow triggers (ksf_Workflow):
   - Create project from template
   - Link project to customer
   - Notify delivery team
   - Assign project manager
4. Project created in ksf_ProjectManagement
5. Customer notified of project start
6. Project milestones visible in CRM

---

## UC-CRM-008: Customer Credit Review
**Actor**: Account Manager, Finance

**Trigger**: Credit limit request, periodic review

**Flow**:
1. Account manager requests credit increase
2. System compiles customer history:
   - Payment history
   - Outstanding invoices
   - Order frequency
   - Lifetime value
3. Finance reviews credit report
4. Approves or adjusts credit limit
5. Customer notified of new limit

---

## UC-CRM-009: Import Customer Data
**Actor**: System Administrator

**Trigger**: Data migration, third-party import

**Flow**:
1. Admin initiates import
2. System accepts CSV/Excel file
3. Field mapping interface displayed
4. Admin maps import fields to CRM fields
5. System validates data:
   - Required fields present
   - Format validation
   - Duplicate detection
6. Preview of import results
7. Admin confirms import
8. Records created/updated
9. Import report generated

---

## UC-CRM-010: Generate Sales Report
**Actor**: Sales Manager, Management

**Flow**:
1. Navigate to Reports > Sales
2. Select report type:
   - Pipeline by stage
   - Win/loss analysis
   - Rep performance
   - Forecast accuracy
3. Set date range and filters
4. Generate report
5. Export options: PDF, Excel, CSV
6. Schedule recurring reports (weekly, monthly)

---

## UC-CRM-011: Email Campaign Integration
**Actor**: Marketing, System

**Trigger**: Customer added to email list

**Flow**:
1. Customer added to segment
2. Segment linked to email campaign
3. System creates email list (ksf_EmailManager)
4. Campaign emails sent:
   - Open tracking
   - Click tracking
5. Engagement logged to CRM:
   - Email opened
   - Link clicked
   - Unsubscribed
6. Sales rep sees customer engagement score

---

## UC-CRM-012: Customer Merge/Deduplication
**Actor**: Sales Rep, System

**Trigger**: Duplicate detection alert

**Flow**:
1. System detects potential duplicate:
   - Same email
   - Similar company name
   - Same phone number
2. Alert sent to sales rep
3. Rep reviews duplicate candidates
4. Options:
   - Keep both (false positive)
   - Merge into one record
   - Primary vs secondary relationship
5. If merged:
   - Data combined
   - History preserved
   - Contact marked as merged
   - Activity linked to master record

*Document Version: 1.0.0*
*Last Updated: 2026-05-24*