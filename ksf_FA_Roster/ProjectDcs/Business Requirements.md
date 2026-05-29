# Business Requirements - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft
- **Author**: KSFII Development Team

---

## 1. Project Overview

ksf_FA_Roster is a FrontAccounting adapter module that provides employee roster/scheduling management functionality integrated with FA's employee and time tracking data. It bridges business logic with FA's hook system, page rendering, and database adapters.

## 2. Adapter Pattern

```
┌─────────────────────────────────────────────────────────┐
│                    FA Adapter Layer                     │
│  ksf_FA_Roster/                                        │
│    ├── hooks.php        → Module registration          │
│    ├── pages/           → FA page handlers             │
│    ├── Integration/     → FA database adapters          │
│    └── src/             → Business logic adaptation     │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                  Business Logic Layer                   │
│  ksf_Roster/                                            │
│    ├── Entity/        → Shift, Roster, TimeEntry        │
│    └── Service/       → RosterService                   │
└─────────────────────────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────┐
│                  FrontAccounting Core                   │
│  (employees, timesheets, dimensions)                     │
└─────────────────────────────────────────────────────────┘
```

## 3. Problem Statement

- Need to manage employee work schedules
- Shift coverage must meet operational needs
- Time clock integration with FA payroll
- Shift swap requests and approvals
- Overtime tracking and management
- Integration with travel/expense for roaming staff

## 4. Stakeholders

- HR Department (roster management)
- Department Managers (coverage planning)
- Employees (view schedule, request swaps)
- Payroll (time clock data)
- Finance (overtime costs)

## 5. Core Functionality

### 5.1 Roster Management
- Weekly/monthly roster creation
- Shift templates for common patterns
- Copy previous week functionality
- Publish roster to employees
- Department-based rosters

### 5.2 Shift Management
- Define shift types (morning, afternoon, night, custom)
- Shift duration and break times
- Required staffing levels per shift
- Shift assignment to employees
- Overlap and coverage management

### 5.3 Time Clock Integration
- Punch in/out functionality
- Link to FA timesheet system
- Break tracking
- Overtime calculation
- Attendance reports

### 5.4 Shift Swap Management
- Employee swap requests
- Manager approval workflow
- Swap visibility and tracking
- Conflict detection

### 5.5 Coverage Analysis
- Minimum coverage requirements
- Gap detection
- Coverage alerts
- Staffing reports

## 6. FA Integration

### 6.1 Hook Integration
- `install_access()` - Security sections/areas
- `install_options()` - Menu items
- `activate_extension()` - Database setup

### 6.2 Database Adapters
- Employee data for assignment
- Timesheet integration for payroll
- Dimension/department for roster grouping

### 6.3 Permission Model

| Permission | Description |
|------------|-------------|
| ROSTER_VIEW | View rosters |
| ROSTER_MANAGE | Create/edit rosters |
| ROSTER_APPROVE | Approve swaps, overtime |
| ROSTER_ADMIN | Full administration |

## 7. Dependencies

| Module | Relationship | Purpose |
|--------|--------------|---------|
| ksf_Roster | Business Logic | Core functionality |
| ksf_Calendar | Integration | Shift events, calendar sync |
| ksf_TravelExpense | Integration | Travel claims for roaming |
| ksf_Payroll | Integration | Time clock data |
| ksf_TimeTracking | Integration | Attendance tracking |

## 8. Success Metrics

- Coverage rate > 95%
- Shift swap approval < 24 hours
- Time clock accuracy > 99%
- Roster publishing < 5 minutes
- Overtime within budget

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*