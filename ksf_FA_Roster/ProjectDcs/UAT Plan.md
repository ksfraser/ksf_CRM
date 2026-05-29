# UAT Plan - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate FrontAccounting adapter functionality for roster management.

### 1.2 Prerequisites
- FrontAccounting installed
- ksf_Roster business logic installed
- ksf_Calendar installed (shift events)
- FA company with departments and employees

### 1.3 Test Users
- Department Manager (SA_ROSTER_MANAGE)
- Employee (SA_ROSTER_VIEW)
- HR (SA_ROSTER_ADMIN)
- Approver (SA_ROSTER_APPROVE)

---

## 2. UAT Scenarios

### UAT-ROS-001: Create Weekly Roster
**Scenario**: Department Manager creates roster for the week

**Steps**:
1. Login as Department Manager
2. Navigate to HRM > Roster
3. Select department: "Engineering"
4. Select week: May 11-17, 2026
5. System creates draft roster
6. Add shifts for each day
7. Assign employees to shifts
8. Click "Publish Roster"
9. Verify employees see their schedules

**Expected Results**:
- [ ] Roster created in draft status
- [ ] Shifts added for all days
- [ ] Employees assigned to shifts
- [ ] Roster published successfully
- [ ] Coverage percentage displayed

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-002: Copy Previous Week
**Scenario**: Department Manager copies last week's roster

**Steps**:
1. Navigate to HRM > Roster
2. Select department and new week
3. Click "Copy from Previous Week"
4. Confirm copy
5. Review and adjust as needed
6. Publish roster

**Expected Results**:
- [ ] Previous week shifts copied
- [ ] Same employees assigned
- [ ] Adjustments can be made
- [ ] Published successfully

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-003: Assign Shift
**Scenario**: Department Manager assigns employee to shift

**Steps**:
1. Open roster in draft status
2. Click on empty shift slot (Monday 9AM)
3. Employee picker opens
4. Search and select "John Smith"
5. Confirm assignment
6. Shift displays with employee name

**Expected Results**:
- [ ] Employee picker opens
- [ ] Search finds correct employee
- [ ] Shift assigned successfully
- [ ] Employee name displayed on shift

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-004: Employee Punches In
**Scenario**: Employee clocks in for shift

**Steps**:
1. Login as Employee
2. Navigate to HRM > Time Clock
3. View today's shift: "Monday May 11, 09:00-17:00"
4. Click "Punch In"
5. System records current time

**Expected Results**:
- [ ] Today's shift displayed
- [ ] Punch In button available
- [ ] Time recorded successfully
- [ ] Shift status changes to "Confirmed"

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-005: Employee Punches Out
**Scenario**: Employee clocks out after shift

**Steps**:
1. Employee finishes work at 17:30
2. Navigate to Time Clock
3. View active shift
4. Click "Punch Out"
5. System calculates worked hours

**Expected Results**:
- [ ] Punch Out button available
- [ ] Time recorded
- [ ] Hours calculated (8.5 hrs)
- [ ] Break time deducted
- [ ] Timesheet entry created

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-006: Request Shift Swap
**Scenario**: Employee requests to swap shift with colleague

**Steps**:
1. Login as Employee
2. Navigate to "My Schedule"
3. Find shift to swap: Tuesday May 12
4. Click "Request Swap"
5. Enter reason: "Personal appointment"
6. Optionally select colleague: "Jane Doe"
7. Submit request
8. View pending request status

**Expected Results**:
- [ ] Swap request form opens
- [ ] Reason can be entered
- [ ] Colleague can be selected
- [ ] Request submitted
- [ ] Request status = Pending

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-007: Approve Shift Swap
**Scenario**: Manager approves employee swap request

**Steps**:
1. Login as Department Manager
2. Navigate to HRM > Swap Requests
3. View pending request from John Smith
4. Review swap details:
   - Tuesday May 12
   - Jane Doe as replacement
5. Click "Approve"
6. Confirm approval

**Expected Results**:
- [ ] Request details displayed
- [ ] Approve button available
- [ ] Request approved
- [ ] Shift updated with new employee
- [ ] Both employees notified

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-008: View Coverage Report
**Scenario**: Manager reviews coverage for the week

**Steps**:
1. Navigate to HRM > Roster
2. Select department and week
3. Click "Coverage Report"
4. View daily coverage:
   - Monday: 100%
   - Tuesday: 85%
   - Wednesday: 70% (ALERT)
5. Click on Wednesday for details

**Expected Results**:
- [ ] Coverage report displays
- [ ] Daily percentages shown
- [ ] Alerts for low coverage
- [ ] Details accessible on click

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-009: Handle Coverage Alert
**Scenario**: System alerts manager about low coverage

**Steps**:
1. System detects: Wednesday coverage 70% (minimum 80%)
2. Alert generated
3. Manager receives notification
4. Manager opens coverage report
5. Views gaps on Wednesday
6. Assigns additional employee to cover gap
7. Coverage increases to 85%

**Expected Results**:
- [ ] Alert triggered automatically
- [ ] Notification sent
- [ ] Manager can view gap
- [ ] Additional assignment possible
- [ ] Coverage improved

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-010: Define Shift Template
**Scenario**: HR Admin creates new shift type

**Steps**:
1. Login as HR Admin
2. Navigate to Setup > Roster Setup
3. Click "Shift Types"
4. Click "Add Shift Type"
5. Enter details:
   - Name: "Late Night Shift"
   - Start: 22:00
   - End: 06:00
   - Break: 30 min
   - Color: #9b59b6
6. Save

**Expected Results**:
- [ ] Form displays correctly
- [ ] All fields validated
- [ ] Shift type created
- [ ] Available for roster creation

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-011: View Overtime Status
**Scenario**: Manager reviews employee overtime

**Steps**:
1. Navigate to HRM > Overtime
2. Select employee: "John Smith"
3. View week summary:
   - Regular hours: 40
   - Overtime: 2.5 hours
4. Alert displayed

**Expected Results**:
- [ ] Overtime view accessible
- [ ] Hours calculated correctly
- [ ] Alert displayed for OT
- [ ] Export available

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-012: Lock Roster
**Scenario**: HR locks roster after week ends

**Steps**:
1. Week completes (all time entries recorded)
2. Manager opens roster
3. Clicks "Lock Roster"
4. System validates all entries complete
5. Manager confirms lock
6. Roster status = Locked

**Expected Results**:
- [ ] Lock button available
- [ ] Validation runs
- [ ] Confirmation dialog
- [ ] Roster locked
- [ ] Ready for payroll

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