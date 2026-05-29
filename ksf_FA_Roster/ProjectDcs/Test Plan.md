# Test Plan - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
Test plan for FA adapter module covering roster management, shift assignment, time clock, and swap workflow.

### 1.2 Scope
- Entity behavior testing
- FA integration testing
- Hook validation
- Database adapter testing
- Workflow validation

---

## 2. Unit Tests

### 2.1 Roster Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-ENT-001 | Create roster with required fields | Roster created with ID |
| ROS-ENT-002 | Create roster without department | ValidationException |
| ROS-ENT-003 | Set week start date | Date set |
| ROS-ENT-004 | Set status workflow | Valid transitions |
| ROS-ENT-005 | Publish roster | Status = published |
| ROS-ENT-006 | Lock roster | Status = locked |
| ROS-ENT-007 | Get department data | Returns FA dimension |
| ROS-ENT-008 | Get employees | Returns employee array |
| ROS-ENT-009 | Calculate coverage rate | Returns percentage |
| ROS-ENT-010 | Calculate total hours | Returns decimal sum |

### 2.2 Shift Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SHIFT-001 | Create shift with required fields | Shift created |
| ROS-SHIFT-002 | Create shift without employee | ValidationException |
| ROS-SHIFT-003 | Set shift times | Times set |
| ROS-SHIFT-004 | Set break duration | Break minutes set |
| ROS-SHIFT-005 | Assign employee | Employee linked |
| ROS-SHIFT-006 | Change employee assignment | Employee updated |
| ROS-SHIFT-007 | Calculate worked hours | Returns hours |
| ROS-SHIFT-008 | Cancel shift | Status = cancelled |
| ROS-SHIFT-009 | Link to swap request | Swap linked |

### 2.3 ShiftType Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-TYPE-001 | Create shift type | Type created |
| ROS-TYPE-002 | Set default times | Start/end set |
| ROS-TYPE-003 | Set color code | Color stored |
| ROS-TYPE-004 | Activate/deactivate | is_active toggled |

### 2.4 SwapRequest Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SWAP-001 | Create swap request | Request created |
| ROS-SWAP-002 | Set requesting employee | Employee set |
| ROS-SHIFT-003 | Set target shift | Shift linked |
| ROS-SWAP-004 | Approve swap | Status = approved |
| ROS-SWAP-005 | Reject swap | Status = rejected |
| ROS-SWAP-006 | Execute swap | Shifts updated |

### 2.5 TimeEntry Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-TIME-001 | Create time entry | Entry created |
| ROS-TIME-002 | Set punch in | Time set |
| ROS-TIME-003 | Set punch out | Time set |
| ROS-TIME-004 | Calculate worked hours | Returns hours |
| ROS-TIME-005 | Calculate overtime | Returns OT hours |
| ROS-TIME-006 | Link to shift | Shift linked |

---

## 3. FA Integration Tests

### 3.1 hooks.php Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-FA-HOOK-001 | install_access returns valid array | Security areas defined |
| ROS-FA-HOOK-002 | install_options adds menu items | Menu items in HRM |
| ROS-FA-HOOK-003 | activate_extension creates tables | Tables created |
| ROS-FA-HOOK-004 | deactivate_extension cleanup | Tables preserved |

### 3.2 EmployeeAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-FA-EMP-001 | Get employees by department | Returns FA employees |
| ROS-FA-EMP-002 | Get single employee | Returns employee data |
| ROS-FA-EMP-003 | Get employee schedule | Returns shift array |
| ROS-FA-EMP-004 | Get employee availability | Returns availability |
| ROS-FA-EMP-005 | Get active employees | Returns employee list |

### 3.3 TimesheetAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-FA-TS-001 | Get timesheet entries | Returns entries |
| ROS-FA-TS-002 | Create timesheet entry | Entry created, ID returned |
| ROS-FA-TS-003 | Get punch records | Returns attendance |
| ROS-FA-TS-004 | Calculate weekly hours | Returns total |
| ROS-FA-TS-005 | Get overtime hours | Returns OT amount |

---

## 4. Service Tests

### 4.1 RosterService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SVC-001 | Create roster | Roster persisted |
| ROS-SVC-002 | Copy week | Roster populated |
| ROS-SVC-003 | Publish roster | Status = published |
| ROS-SVC-004 | Lock roster | Status = locked |
| ROS-SVC-005 | Get week view | Returns shift array |
| ROS-SVC-006 | Get roster stats | Returns statistics |

### 4.2 ShiftService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SVC-SHIFT-001 | Create shift | Shift persisted |
| ROS-SVC-SHIFT-002 | Assign employee | Employee linked |
| ROS-SVC-SHIFT-003 | Update shift | Shift updated |
| ROS-SVC-SHIFT-004 | Delete shift | Shift removed |
| ROS-SVC-SHIFT-005 | Bulk create shifts | Multiple shifts created |

### 4.3 SwapService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SVC-SWAP-001 | Create swap request | Request created |
| ROS-SVC-SWAP-002 | Approve swap | Status updated, shifts swapped |
| ROS-SVC-SWAP-003 | Reject swap | Status = rejected |
| ROS-SVC-SWAP-004 | Get pending swaps | Returns request list |

### 4.4 TimeClockService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-SVC-TIME-001 | Punch in | Entry created |
| ROS-SVC-TIME-002 | Punch out | Entry completed |
| ROS-SVC-TIME-003 | Get time entries | Returns entries |
| ROS-SVC-TIME-004 | Calculate overtime | Returns OT hours |

---

## 5. Presenter Tests

### 5.1 RosterCalendarPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-PRES-CAL-001 | Get week view | Returns week data |
| ROS-PRES-CAL-002 | Get day view | Returns day data |
| ROS-PRES-CAL-003 | Publish roster | Publish success |
| ROS-PRES-CAL-004 | Copy week | Copy success |
| ROS-PRES-CAL-005 | Get stats | Returns statistics |

### 5.2 ShiftManagementPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-PRES-SHIFT-001 | Get shifts (filtered) | Returns filtered list |
| ROS-PRES-SHIFT-002 | Create shift | Shift created |
| ROS-PRES-SHIFT-003 | Update shift | Shift updated |
| ROS-PRES-SHIFT-004 | Get templates | Returns type list |

### 5.3 CoveragePresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-PRES-COV-001 | Get coverage report | Returns coverage data |
| ROS-PRES-COV-002 | Get coverage gaps | Returns gap array |
| ROS-PRES-COV-003 | Get coverage alerts | Returns alert list |
| ROS-PRES-COV-004 | Get minimum coverage | Returns requirements |

### 5.4 TimeClockPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-PRES-TIME-001 | Get time entries | Returns entries |
| ROS-PRES-TIME-002 | Punch in | Entry created |
| ROS-PRES-TIME-003 | Punch out | Entry completed |
| ROS-PRES-TIME-004 | Get weekly hours | Returns total |
| ROS-PRES-TIME-005 | Get overtime status | Returns OT data |

---

## 6. AJAX Handler Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-AJAX-001 | ros_week request | JSON week data |
| ROS-AJAX-002 | ros_shift_create request | JSON created ID |
| ROS-AJAX-003 | ros_shift_assign request | JSON success |
| ROS-AJAX-004 | ros_publish request | JSON success |
| ROS-AJAX-005 | ros_swap_request request | JSON request ID |
| ROS-AJAX-006 | ros_swap_approve request | JSON success |
| ROS-AJAX-007 | ros_punch_in request | JSON entry ID |
| ROS-AJAX-008 | ros_punch_out request | JSON completed entry |
| ROS-AJAX-009 | ros_coverage request | JSON coverage data |
| ROS-AJAX-010 | Invalid request | 400 error |

---

## 7. Integration Tests

### 7.1 With ksf_Roster (Business Logic)

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-INT-BIZ-001 | Service injection | Service available |
| ROS-INT-BIZ-002 | CRUD operations | Data persisted |

### 7.2 With ksf_Calendar

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-INT-CAL-001 | Create shift events | Events created |
| ROS-INT-CAL-002 | Sync roster changes | Events updated |

### 7.3 With Payroll

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| ROS-INT-PAY-001 | Timesheet integration | Entries created |
| ROS-INT-PAY-002 | Overtime data | OT hours sent |

---

## 8. Test Data Fixtures

```php
$rosterData = [
    'id' => 1,
    'department_id' => 5,
    'week_start' => '2026-05-11',
    'status' => 'draft',
    'created_by' => 'manager_001'
];

$shiftData = [
    'id' => 1,
    'roster_id' => 1,
    'employee_id' => 101,
    'shift_date' => '2026-05-11',
    'start_time' => '09:00:00',
    'end_time' => '17:00:00',
    'break_minutes' => 30,
    'status' => 'assigned'
];

$shiftTypeData = [
    'id' => 1,
    'name' => 'Morning Shift',
    'start_time' => '09:00:00',
    'end_time' => '17:00:00',
    'break_minutes' => 30,
    'color_code' => '#3498db'
];

$employeeData = [
    'id' => 101,
    'name' => 'John Smith',
    'department_id' => 5,
    'employee_code' => 'EMP001'
];
```

---

## 9. Test Execution

```bash
# Run all tests
composer test

# Run unit tests
./vendor/bin/phpunit tests/Unit/ksf_FA_Roster

# Run FA integration tests
./vendor/bin/phpunit tests/Integration/FA

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/ tests/
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*