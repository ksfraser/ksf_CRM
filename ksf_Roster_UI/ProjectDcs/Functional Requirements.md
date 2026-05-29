# Functional Requirements - ksf_Roster_UI

## Document Information
- **Module**: ksf_Roster_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Roster Calendar Components

| Component | Type | Description |
|-----------|------|-------------|
| WeekSelector | Selector | Navigate weeks |
| RosterGrid | Grid | 7-day schedule grid |
| ShiftBlock | Block | Employee shift block |
| EmployeeRow | Row | Per-employee row |
| PublishButton | Button | Publish roster |
| CopyWeekButton | Button | Copy from previous |

### 1.2 Shift Management Components

| Component | Type | Description |
|-----------|------|-------------|
| ShiftTable | Table | Shift definitions |
| ShiftForm | Form | Create/edit shift |
| TemplateSelect | Select | Shift template |
| StartTimePicker | Time | Shift start |
| EndTimePicker | Time | Shift end |
| BreakDuration | Input | Break minutes |

### 1.3 Coverage Components

| Component | Type | Description |
|-----------|------|-------------|
| CoverageChart | Chart | Coverage graph |
| GapAlert | Alert | Under-staffed warning |
| HeadcountGrid | Grid | Required vs actual |
| CoverageBadge | Badge | Coverage % indicator |

### 1.4 Time Clock Components

| Component | Type | Description |
|-----------|------|-------------|
| ClockInButton | Button | Punch in |
| ClockOutButton | Button | Punch out |
| CurrentShift | Display | Active shift info |
| TimeDisplay | Clock | Current time |
| HoursSummary | Card | Weekly hours |

---

## 2. AJAX Endpoints

### 2.1 Roster

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `ros_week_view` | GET | dept_id, week | Calendar data |
| `ros_shift_create` | POST | shiftData | Created shift |
| `ros_shift_update` | POST | shift_id, data | Updated shift |
| `ros_shift_delete` | POST | shift_id | Deleted |
| `ros_publish` | POST | dept_id, week | Published |

### 2.2 Swaps

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `ros_swap_request` | POST | shift_id, target_emp | Request created |
| `ros_swap_approve` | POST | request_id | Approved |
| `ros_swap_reject` | POST | request_id | Rejected |

### 2.3 Time Clock

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `ros_punch_in` | POST | employee_id | TimeEntry |
| `ros_punch_out` | POST | entry_id | Updated entry |
| `ros_entries` | GET | employee_id, date | Entry list |
| `ros_weekly_hours` | GET | employee_id, week | Hours |

### 2.4 Coverage

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `ros_coverage` | GET | dept_id, date | Coverage report |
| `ros_gaps` | GET | dept_id, date | Gap analysis |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| start_time | Valid time | Required |
| end_time | After start | Invalid end time |
| employee_id | Required | Required |
| department_id | Required | Required |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| employee_id | FK validation | Invalid employee |
| shift_template_id | FK validation | Invalid template |
| conflict_check | No overlap | Shift conflict |

---

## 4. Integration Requirements

- ksf_Calendar: Shift events
- ksf_TravelExpense: Travel claims

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
