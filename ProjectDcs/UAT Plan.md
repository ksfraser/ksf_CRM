# UAT Plan - ksf_CRM

## Document Information
- **Module**: ksf_CRM
- **Version**: 1.0.0
- **Date**: 2026-05-11

## 1. UAT Overview

### 1.1 Purpose
Validate CRM functionality: customer management, contact management, opportunity pipeline, and communication tracking.

### 1.2 Modules Integrated
- ksf_ProjectManagement
- ksf_SupportTickets
- ksf_EmailManager
- ksf_Calendar
- ksf_Workflow

## 2. UAT Scenarios

### UAT-CRM-001: Create Customer
**Scenario**: Sales rep creates new customer

**Steps**:
1. Navigate to CRM > Customers > New
2. Enter customer details (name, type, segment, territory)
3. Assign account manager
4. Save customer
5. Add primary contact
6. Verify customer appears in list

**Expected Results**:
- [ ] Customer created with ID
- [ ] Contact linked
- [ ] Timeline shows creation event

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CRM-002: Create Opportunity
**Scenario**: Sales rep creates sales opportunity

**Steps**:
1. Navigate to customer > Opportunities
2. Create opportunity (name, amount, probability, stage)
3. Set expected close date
4. Save
5. Move through stages: Prospecting → Qualification → Proposal → Closed Won

**Expected Results**:
- [ ] Opportunity created
- [ ] Pipeline view shows opportunity
- [ ] Stage transitions logged
- [ ] On Closed Won → Project created

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CRM-003: Log Communication
**Scenario**: Log customer call

**Steps**:
1. Navigate to customer record
2. Click "Log Activity"
3. Select "Phone Call"
4. Enter subject, outcome, notes
5. Save
6. Verify appears in timeline

**Expected Results**:
- [ ] Communication logged
- [ ] Timeline shows activity
- [ ] Can filter by type

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CRM-004: Link Support Ticket
**Scenario**: Support ticket linked to customer

**Steps**:
1. Support creates ticket for customer
2. Verify ticket linked to customer
3. View customer → see ticket in timeline
4. Ticket resolved
5. Verify resolution in customer history

**Expected Results**:
- [ ] Ticket linked
- [ ] Visible in customer view
- [ ] Timeline shows ticket history

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CRM-005: Customer Segmentation
**Scenario**: Assign customer to segment, trigger campaign

**Steps**:
1. Define segment criteria (Industry = Technology)
2. Assign customer to segment
3. Create email campaign targeting segment
4. Send campaign
5. View customer → see campaign engagement

**Expected Results**:
- [ ] Segment assignment works
- [ ] Campaign targets correct customers
- [ ] Engagement tracked per customer

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-CRM-006: Follow-Up Reminder
**Scenario**: System sends follow-up reminder

**Steps**:
1. Set "Next Follow-Up" date on opportunity
2. Wait for cron job (or trigger manually)
3. Verify notification sent to assigned rep
4. Complete follow-up, set new date

**Expected Results**:
- [ ] Reminder sent on due date
- [ ] Calendar event created
- [ ] Rep can mark complete

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

## 3. Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| UAT Lead | | | |
| Technical Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*