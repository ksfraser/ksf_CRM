# UAT Plan - ksf_Roster_UI

## Document Information
- **Module**: ksf_Roster_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for roster management.

### 1.2 Prerequisites
- ksf_Roster business logic installed
- FrontAccounting access
- HR Manager or Manager role

---

## 2. UAT Scenarios

### UAT-ROS-001: View Roster Calendar
**Scenario**: Manager views weekly roster

**Steps**:
1. Navigate to HR > Roster Calendar
2. Select "Engineering" department
3. Navigate to current week
4. View shifts for all employees

**Expected Results**:
- [ ] Calendar displays correctly
- [ ] Employee rows visible
- [ ] Shift blocks rendered
- [ ] Week navigation works

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-002: Create Shift
**Scenario**: HR creates shift template

**Steps**:
1. Navigate to Shift Management
2. Click "New Shift"
3. Enter "Night Shift 22-06"
4. Set start/end times
5. Save

**Expected Results**:
- [ ] Shift created
- [ ] Appears in template list
- [ ] Available in roster

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-003: Schedule Employee
**Scenario**: Manager schedules employee

**Steps**:
1. Open Roster Calendar
2. Click on employee cell
3. Select shift from dropdown
4. Repeat for week
5. Click Publish

**Expected Results**:
- [ ] Shift blocks appear
- [ ] Publish successful
- [ ] Notification sent

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-004: Request Shift Swap
**Scenario**: Employee requests swap

**Steps**:
1. Navigate to My Roster
2. Click "Swap" on shift
3. Select colleague
4. Add reason
5. Submit

**Expected Results**:
- [ ] Request created
- [ ] Manager notified
- [ ] Request visible in list

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-005: Time Clock
**Scenario**: Employee punches clock

**Steps**:
1. Navigate to Time Clock
2. Click "Punch In"
3. Verify entry created
4. Later click "Punch Out"

**Expected Results**:
- [ ] Clock in recorded
- [ ] Time displayed
- [ ] Clock out recorded
- [ ] Hours calculated

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ROS-006: Coverage Report
**Scenario**: HR views coverage

**Steps**:
1. Navigate to Coverage Report
2. Select department and date
3. View coverage chart
4. Check gap alerts

**Expected Results**:
- [ ] Report renders
- [ ] Chart displays
- [ ] Gaps highlighted

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
