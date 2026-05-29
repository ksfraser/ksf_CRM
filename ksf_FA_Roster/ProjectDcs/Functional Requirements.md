# Functional Requirements - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
ksf_FA_Roster provides employee roster and scheduling management integrated with FrontAccounting's employee and timesheet data.

### 1.2 Scope
- Roster management
- Shift assignment
- Time clock integration
- Swap request workflow
- Coverage analysis

---

## 2. Core Entities

### 2.1 Roster

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| department_id | int | Yes | FK to FA dimension |
| week_start | Date | Yes | Week starting date |
| status | string | Yes | draft/published/locked/unpublished |
| published_at | DateTime | No | Publication timestamp |
| created_by | string | Yes | User who created |
| created_at | DateTime | Yes | Auto |

### 2.2 Shift

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| roster_id | int | Yes | FK to Roster |
| employee_id | int | Yes | FK to FA employee |
| shift_type_id | int | No | FK to ShiftType |
| shift_date | Date | Yes | Date of shift |
| start_time | Time | Yes | Shift start |
| end_time | Time | Yes | Shift end |
| break_minutes | int | No | Break duration |
| status | string | Yes | assigned/confirmed/completed/swapped/cancelled |
| swap_request_id | int | No | FK to SwapRequest |
| notes | text | No | Additional notes |
| created_at | DateTime | Yes | Auto |

### 2.3 ShiftType

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| name | string | Yes | Type name (Morning, Afternoon, Night) |
| start_time | Time | Yes | Default start |
| end_time | Time | Yes | Default end |
| break_minutes | int | No | Default break |
| color_code | string | No | Calendar color |
| is_active | bool | Yes | Active flag |

### 2.4 SwapRequest

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| requesting_emp_id | int | Yes | Employee requesting |
| target_emp_id | int | No | Employee to swap with |
| shift_id | int | Yes | Shift to swap |
| target_shift_id | int | No | Target shift |
| status | string | Yes | pending/approved/rejected/completed |
| reason | text | No | Reason for swap |
| approved_by | string | No | Approver |
| approved_at | DateTime | No | Approval timestamp |
| created_at | DateTime | Yes | Auto |

### 2.5 TimeEntry

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| employee_id | int | Yes | FK to FA employee |
| shift_id | int | No | FK to Shift |
| punch_date | Date | Yes | Date of punch |
| punch_in | DateTime | Yes | Clock in time |
| punch_out | DateTime | No | Clock out time |
| break_minutes | int | No | Break taken |
| worked_hours | decimal | No | Calculated hours |
| is_overtime | bool | No | Overtime flag |
| created_at | DateTime | Yes | Auto |

---

## 3. Functional Requirements

### FR-ROS-001: Roster Management
**Requirement**: System shall allow roster creation and management.

**Features**:
- Create weekly roster for department
- Copy previous week
- Publish roster to employees
- Lock roster after publication
- Track roster status history

### FR-ROS-002: Shift Assignment
**Requirement**: System shall allow shift assignment to employees.

**Features**:
- Assign employee to shift
- Define shift times and breaks
- Change shift assignment
- Cancel shift
- Bulk shift creation

### FR-ROS-003: Shift Templates
**Requirement**: System shall support shift type templates.

**Features**:
- Define shift types with times
- Set default break duration
- Assign colors for calendar
- Activate/deactivate types
- Use templates for shift creation

### FR-ROS-004: Time Clock Integration
**Requirement**: System shall integrate with FA time tracking.

**Features**:
- Punch in from roster
- Punch out from roster
- Automatic timesheet creation
- Overtime calculation
- Break tracking

### FR-ROS-005: Shift Swap Workflow
**Requirement**: System shall manage shift swap requests.

**Features**:
- Employee requests swap
- Manager approves/rejects
- Swap execution
- Conflict detection
- Swap history

### FR-ROS-006: Coverage Analysis
**Requirement**: System shall track and report coverage.

**Features**:
- Define minimum coverage per shift
- Detect coverage gaps
- Generate coverage alerts
- Coverage reports by department
- Historical coverage data

### FR-ROS-007: FA Employee Integration
**Requirement**: System shall integrate with FA employee data.

**Features**:
- Read employees from FA
- Track employee availability
- Link to timesheet records
- Department-based filtering

### FR-ROS-008: Overtime Management
**Requirement**: System shall track and report overtime.

**Features**:
- Calculate overtime hours
- Overtime threshold configuration
- Overtime alerts
- Overtime reports

---

## 4. User Interactions

### 4.1 Roster Creation Flow

1. Manager navigates to Roster
2. Select department and week
3. System loads or creates roster
4. Assign shifts:
   - Drag employee to shift
   - Or use shift templates
5. Set minimum coverage requirements
6. Publish roster
7. Employees notified

### 4.2 Time Clock Flow

1. Employee arrives at work
2. Navigate to Time Clock
3. Click "Punch In"
4. System records time and links to shift
5. At end of shift:
6. Click "Punch Out"
7. System calculates hours, checks overtime
8. Timesheet entry created

### 4.3 Swap Request Flow

1. Employee needs to swap shift
2. Navigate to Swap Requests
3. Click "Request Swap"
4. Select shift to swap
5. Optionally select colleague
6. Enter reason
7. Submit request
8. Manager receives notification
9. Manager approves/rejects
10. If approved, shifts updated

---

## 5. FA Hook Integration

### 5.1 Security Areas

```php
SS_ROSTER = 117 << 8
SA_ROSTER_VIEW = SS_ROSTER | 1
SA_ROSTER_MANAGE = SS_ROSTER | 2
SA_ROSTER_APPROVE = SS_ROSTER | 3
SA_ROSTER_ADMIN = SS_ROSTER | 4
```

### 5.2 Menu Items

| Menu | Title | Path | Permission |
|------|-------|------|------------|
| HRM | Roster | /modules/.../roster_calendar.php | SA_ROSTER_VIEW |
| HRM | Shift Management | /modules/.../shift_management.php | SA_ROSTER_MANAGE |
| HRM | Time Clock | /modules/.../time_clock.php | SA_ROSTER_VIEW |
| HRM | Swap Requests | /modules/.../swap_requests.php | SA_ROSTER_APPROVE |
| Setup | Roster Setup | /modules/.../setup.php | SA_ROSTER_ADMIN |

---

## 6. Database Integration

### 6.1 Tables

| Table | Purpose |
|-------|---------|
| `{TB_PREF}roster` | Roster records |
| `{TB_PREF}shift_type` | Shift type templates |
| `{TB_PREF}roster_shift` | Individual shift assignments |
| `{TB_PREF}swap_request` | Swap requests |
| `{TB_PREF}time_entry` | Clock punch records |
| `{TB_PREF}coverage_requirement` | Minimum coverage rules |
| `{TB_PREF}coverage_log` | Coverage tracking |

### 6.2 FA Table Usage

| FA Table | Purpose |
|----------|---------|
| `{TB_PREF}employee_active` | Employee data |
| `{TB_PREF}dimensions` | Department grouping |
| `{TB_PREF}timesheet` | Time records for payroll |
| `{TB_PREF}attendance_log` | Attendance tracking |

---

## 7. AJAX Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| ros_week | GET | Weekly roster view |
| ros_day | GET | Daily roster view |
| ros_shift_create | POST | Create shift |
| ros_shift_update | POST | Update shift |
| ros_shift_delete | POST | Delete shift |
| ros_shift_assign | POST | Assign employee |
| ros_publish | POST | Publish roster |
| ros_swap_request | POST | Request swap |
| ros_swap_approve | POST | Approve swap |
| ros_swap_reject | POST | Reject swap |
| ros_punch_in | POST | Clock in |
| ros_punch_out | POST | Clock out |
| ros_coverage | GET | Coverage report |
| ros_overtime | GET | Overtime status |

---

## 8. Composer Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| ksfraser/exceptions | ^1.2 | Exception hierarchy |
| ksfraser/traits | ^1.0 | Trait library |
| ksfraser/roster | * | Business logic |

---

## 9. Exceptions

| Exception | Extends | Description |
|-----------|---------|-------------|
| RosterException | RuntimeException | Base exception |
| RosterNotFoundException | RosterException | Not found |
| ShiftConflictException | RosterException | Employee conflict |
| CoverageViolationException | RosterException | Below minimum |
| DuplicatePunchException | RosterException | Already punched |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*