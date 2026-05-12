# Use Cases - ksf_Roster_UI

---

## UC-ROS-001: View Weekly Roster
**Actor**: Manager

**Preconditions**: Manager has roster access

**Flow**:
1. Navigate to HR > Roster Calendar
2. Select department and week
3. View employee shifts
4. Check coverage percentages
5. Identify gaps

---

## UC-ROS-002: Create Shift
**Actor**: HR Manager

**Preconditions**: Department selected

**Flow**:
1. Navigate to Shift Management
2. Click "New Shift"
3. Enter shift details:
   - Name (e.g., "Morning 6-14")
   - Start time
   - End time
   - Break duration
4. Assign to department
5. Save shift template

---

## UC-ROS-003: Schedule Employee
**Actor**: Manager

**Preconditions**: Shifts defined

**Flow**:
1. Open Roster Calendar
2. Click on employee's cell for day
3. Select shift from list
4. Shift block appears
5. Repeat for other days
6. Publish roster

---

## UC-ROS-004: Request Shift Swap
**Actor**: Employee

**Preconditions**: Employee has scheduled shift

**Flow**:
1. Navigate to My Roster
2. View upcoming shifts
3. Click "Request Swap" on shift
4. Select colleague to swap with
5. Add reason
6. Submit request
7. Manager notified

---

## UC-ROS-005: Approve Shift Swap
**Actor**: Manager

**Preconditions**: Swap request pending

**Flow**:
1. Navigate to Swap Requests
2. View request details
3. Check coverage impact
4. Approve or reject
5. If approved, both employees notified
6. Roster updated

---

## UC-ROS-006: Punch Time Clock
**Actor**: Employee

**Preconditions**: Employee has access

**Flow**:
1. Navigate to Time Clock
2. View current status
3. Click "Punch In"
4. System records time
5. At end of shift, click "Punch Out"
6. Hours logged

---

## UC-ROS-007: View Coverage Report
**Actor**: HR Manager

**Preconditions**: Roster exists

**Flow**:
1. Navigate to Coverage Report
2. Select department and date range
3. View coverage graph
4. Identify under-staffed periods
5. Export report

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
