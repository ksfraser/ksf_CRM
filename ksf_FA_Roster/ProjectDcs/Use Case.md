# Use Cases - ksf_FA_Roster

---

## UC-ROS-001: Create Weekly Roster
**Actor**: Department Manager

**Preconditions**: User has SA_ROSTER_MANAGE permission

**Flow**:
1. Navigate to HRM > Roster
2. Select department "Engineering"
3. Select week starting "2026-05-11"
4. System creates or loads roster (Draft status)
5. Add shifts using templates:
   - Monday: 3 Morning, 2 Afternoon
   - Tuesday: 3 Morning, 2 Afternoon
   - ...
6. Drag employees to shift slots
7. View coverage status (shows 95% coverage)
8. Click "Publish Roster"
9. Employees receive notification

**Postconditions**: Roster published, employees notified

---

## UC-ROS-002: Copy Previous Week
**Actor**: Department Manager

**Preconditions**: Previous week roster exists

**Flow**:
1. Navigate to HRM > Roster
2. Select department and week
3. Click "Copy from Previous Week"
4. System copies all shifts with same employees
5. Manager can adjust as needed
6. Publish

**Postconditions**: Roster populated from template

---

## UC-ROS-003: Assign Shift to Employee
**Actor**: Department Manager

**Preconditions**: Roster exists in draft status

**Flow**:
1. Open roster for week
2. Click on empty shift slot
3. Employee selector opens
4. Search for "John Smith"
5. Select employee
6. Shift assigned:
   - Time: 09:00 - 17:00
   - Break: 30 minutes
   - Status: Assigned
7. Coverage automatically updated

**Postconditions**: Shift assigned to employee

---

## UC-ROS-004: Employee Punches In
**Actor**: Employee

**Preconditions**: Employee has shift scheduled today

**Flow**:
1. Employee arrives at workplace
2. Navigate to HRM > Time Clock
3. View today's shift: "09:00 - 17:00"
4. Click "Punch In"
5. System records: punch_in = 08:58
6. Shift status = Confirmed

**Postconditions**: Clock punch recorded, timesheet updated

---

## UC-ROS-005: Employee Punches Out
**Actor**: Employee

**Preconditions**: Employee has punched in

**Flow**:
1. Employee finishes work
2. Navigate to Time Clock
3. View current shift with active punch
4. Click "Punch Out"
5. System records: punch_out = 17:15
6. Calculates:
   - Worked hours: 8.28
   - Break: 0.5 (30 min)
   - Net hours: 7.78
7. Creates timesheet entry

**Postconditions**: Time entry completed, hours calculated

---

## UC-ROS-006: Request Shift Swap
**Actor**: Employee

**Preconditions**: Employee has assigned shift

**Flow**:
1. View own schedule
2. Click on shift "Tuesday May 12"
3. Click "Request Swap"
4. Enter reason: "Medical appointment"
5. Optionally select colleague: "Jane Doe"
6. Submit request
7. Manager receives notification
8. System creates SwapRequest status=Pending

**Postconditions**: Swap request submitted

---

## UC-ROS-007: Approve Shift Swap
**Actor**: Department Manager

**Preconditions**: Swap request exists

**Flow**:
1. Manager receives notification
2. Navigate to HRM > Swap Requests
3. View pending request from John Smith
4. Review:
   - Tuesday May 12, 09:00-17:00
   - Reason: Medical appointment
   - Replaces: Jane Doe
5. Click "Approve"
6. System:
   - Updates shift with new employee
   - Changes SwapRequest status to Approved
   - Notifies both employees

**Postconditions**: Shift swapped, both notified

---

## UC-ROS-008: View Coverage Report
**Actor**: Department Manager, HR

**Preconditions**: Roster exists

**Flow**:
1. Navigate to HRM > Roster
2. Select department and week
3. Click "Coverage Report"
4. System displays:
   - Daily coverage percentage
   - Gap highlights
   - Minimum vs actual
5. Click on gap for details
6. Export report

**Postconditions**: Coverage report displayed

---

## UC-ROS-009: Handle Coverage Alert
**Actor**: System, Manager

**Trigger**: Coverage falls below minimum

**Flow**:
1. System checks roster for Wednesday
2. Coverage: 70% (minimum 80%)
3. System generates alert:
   - "Wednesday coverage below minimum"
   - "3 additional staff needed"
4. Manager receives alert
5. Manager reviews options:
   - Contact available employees
   - Request volunteers
   - Adjust shift requirements
6. Manager assigns additional shifts

**Postconditions**: Coverage restored

---

## UC-ROS-010: Define Shift Template
**Actor**: HR Admin

**Preconditions**: Admin permission

**Flow**:
1. Navigate to Setup > Roster Setup
2. Click "Shift Types"
3. Click "Add Shift Type"
4. Enter:
   - Name: "Late Shift"
   - Start time: 14:00
   - End time: 22:00
   - Break: 30 min
   - Color: #e74c3c
5. Save

**Postconditions**: Shift type available for roster

---

## UC-ROS-011: View Overtime Status
**Actor**: Department Manager, Payroll

**Preconditions**: Time entries exist

**Flow**:
1. Navigate to Roster > Overtime
2. Select employee "John Smith"
3. View week:
   - Monday: 8.0 hrs (0 OT)
   - Tuesday: 10.0 hrs (2.0 OT)
   - Wednesday: 8.0 hrs (0 OT)
   - Thursday: 8.0 hrs (0 OT)
   - Friday: 8.0 hrs (0 OT)
4. Total: 42.0 hrs regular, 2.0 hrs OT
5. Alert: "John has exceeded 40-hour threshold"

**Postconditions**: Overtime status visible

---

## UC-ROS-012: Lock Roster
**Actor**: Department Manager, HR

**Preconditions**: Roster published and week complete

**Flow**:
1. Week ends, all shifts completed
2. Manager reviews roster
3. Click "Lock Roster"
4. System validates:
   - All shifts have time entries
   - No outstanding swaps
   - Coverage requirements met
5. Confirm lock
6. Roster status = Locked
7. Data ready for payroll processing

**Postconditions**: Roster locked, ready for payroll

---

## UC-ROS-013: Cancel Shift
**Actor**: Department Manager

**Preconditions**: Shift exists in draft roster

**Flow**:
1. Open roster in draft status
2. Find shift "Monday 9AM"
3. Click "Cancel Shift"
4. Confirm cancellation
5. System:
   - Removes shift
   - Notifies assigned employee
   - Updates coverage

**Postconditions**: Shift removed, employee notified

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*