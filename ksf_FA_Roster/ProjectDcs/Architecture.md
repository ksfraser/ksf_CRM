# Architecture - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_FA_Roster provides FrontAccounting integration for employee roster and scheduling management, leveraging FA's employee and timesheet data.

### 1.1 Namespace
```php
Ksfraser\FA\Roster\
```

### 1.2 Layer Pattern
```
ksf_FA_Roster/
├── composer.json
├── AGENTS.md
├── hooks.php                      → FA hooks (extension)
├── ProjectDcs/                    ← THIS DOCUMENTATION
├── pages/
│   ├── roster_calendar.php        → Weekly/monthly calendar
│   ├── shift_management.php       → Shift CRUD
│   ├── coverage_report.php        → Coverage analysis
│   ├── time_clock.php             → Punch in/out
│   ├── swap_requests.php          → Swap management
│   └── overtime_requests.php       → OT approvals
├── Integration/
│   ├── EmployeeAdapter.php         → FA employee integration
│   ├── TimesheetAdapter.php        → FA timesheet integration
│   └── DepartmentAdapter.php       → FA dimension integration
└── src/
    └── Ksfraser/FA/Roster/
        ├── Presenter/
        │   ├── RosterCalendarPresenter.php
        │   ├── ShiftManagementPresenter.php
        │   ├── CoveragePresenter.php
        │   └── TimeClockPresenter.php
        ├── Component/
        │   ├── RosterGrid.php
        │   ├── ShiftCard.php
        │   ├── TimeClockWidget.php
        │   ├── CoverageIndicator.php
        │   ├── SwapRequestCard.php
        │   └── OvertimeAlert.php
        └── Handler/
            └── RosterAjaxHandler.php
```

---

## 2. FA Hook Integration

### 2.1 hooks.php Structure

```php
class hooks_ksf_fa_roster extends hooks {
    var $module_name = 'ksf_FA_Roster';

    function install_access() {
        $security_sections['SS_ROSTER'] = _("Roster Management");
        $security_areas['SA_ROSTER_VIEW'] = array(SS_ROSTER | 1, _("View Roster"));
        $security_areas['SA_ROSTER_MANAGE'] = array(SS_ROSTER | 2, _("Manage Roster"));
        $security_areas['SA_ROSTER_APPROVE'] = array(SS_ROSTER | 3, _("Approve Swaps/Overtime"));
        return array($security_areas, $security_sections);
    }

    function install_options($app) {
        switch($app->id) {
            case 'hrm':
                $app->add_lapp_function(0, _("Roster"),
                    $path_to_root."/modules/ksf_FA_Roster/roster_calendar.php",
                    'SA_ROSTER_VIEW', MENU_MAIN);
                $app->add_lapp_function(1, _("Shift Management"),
                    $path_to_root."/modules/ksf_FA_Roster/shift_management.php",
                    'SA_ROSTER_MANAGE', MENU_ENTRY);
                $app->add_lapp_function(1, _("Time Clock"),
                    $path_to_root."/modules/ksf_FA_Roster/time_clock.php",
                    'SA_ROSTER_VIEW', MENU_ENTRY);
                $app->add_rapp_function(2, _("Roster Setup"),
                    $path_to_root."/modules/ksf_FA_Roster/setup.php",
                    'SA_ROSTER_ADMIN', MENU_MAINTENANCE);
                break;
        }
    }

    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        return $this->update_databases($company, $updates, $check_only);
    }
}
```

---

## 3. Database Adapters

### 3.1 EmployeeAdapter

```php
namespace Ksfraser\FA\Roster\Integration;

class EmployeeAdapter {
    public function getEmployeesByDepartment(string $deptId): array;
    public function getEmployee(string $empId): ?array;
    public function getEmployeeSchedule(string $empId, \DateTime $start, \DateTime $end): array;
    public function getEmployeeAvailability(string $empId): array;
    public function getActiveEmployees(): array;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getEmployeesByDepartment | employee_active | Staff list |
| getEmployeeSchedule | roster_shifts | Shift assignments |
| getEmployeeAvailability | employee_leave | Leave/availability |

### 3.2 TimesheetAdapter

```php
class TimesheetAdapter {
    public function getTimesheetEntries(string $empId, \DateTime $date): array;
    public function createTimesheetEntry(array $data): int;
    public function getPunchRecords(string $empId, \DateTime $date): array;
    public function calculateHours(string $empId, \DateTime $week): array;
    public function getOvertimeHours(string $empId, \DateTime $period): float;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getTimesheetEntries | timesheet | Time records |
| createTimesheetEntry | timesheet | Record punch |
| getPunchRecords | attendance_log | Attendance |
| calculateHours | timesheet | Weekly total |

---

## 4. Entity Adaptation

### 4.1 Roster (FA Adapted)

```php
namespace Ksfraser\FA\Roster\Entity;

class Roster {
    private string $id;
    private string $departmentId;     // FA dimension
    private \DateTime $weekStart;
    private RosterStatus $status;
    private array $shifts;           // Collection
    private ?\DateTime $publishedAt;
    private string $createdBy;

    // FA integration methods
    public function getDepartment(): ?array;
    public function getEmployees(): array;
    public function getCoverageRate(): float;
    public function calculateTotalHours(): float;
}
```

### 4.2 Shift (FA Adapted)

```php
class Shift {
    private string $id;
    private string $rosterId;
    private string $employeeId;      // FA employee
    private string $shiftTypeId;
    private \DateTime $date;
    private \DateTime $startTime;
    private \DateTime $endTime;
    private int $breakMinutes;
    private float $hours;
    private ShiftStatus $status;
    private ?string $swapRequestId;

    // FA integration methods
    public function getEmployee(): ?array;
    public function getTimesheetEntries(): array;
    public function calculateWorkedHours(): float;
}
```

### 4.3 ShiftType

```php
class ShiftType {
    private string $id;
    private string $name;
    private \DateTime $defaultStart;
    private \DateTime $defaultEnd;
    private int $breakMinutes;
    private string $colorCode;
    private bool $isActive;
}
```

### 4.4 TimeEntry

```php
class TimeEntry {
    private string $id;
    private string $employeeId;      // FA employee
    private \DateTime $date;
    private \DateTime $punchIn;
    private \DateTime $punchOut;
    private float $breakMinutes;
    private float $workedHours;
    private ?string $shiftId;
    private bool $isOvertime;

    public function calculateOvertime(): float;
}
```

---

## 5. State Machines

### 5.1 Roster Status

```
Draft ──> Published ──> Locked
    │         │
    └─────────┴──> Unpublished
```

### 5.2 Shift Status

```
Assigned ──> Confirmed ──> Completed
     │            │
     └─> Swapped ─┘
     └─> Cancelled
```

### 5.3 Swap Request Status

```
Pending ──> Approved ──> Completed
    │
    └─> Rejected
```

---

## 6. Presenter Layer

### 6.1 RosterCalendarPresenter

```php
class RosterCalendarPresenter {
    public function getWeekView(string $departmentId, \DateTime $week): array;
    public function getDayView(string $departmentId, \DateTime $date): array;
    public function publishRoster(string $rosterId): bool;
    public function copyWeek(string $fromWeek, string $toWeek): bool;
    public function getRosterStats(string $rosterId): array;
}
```

### 6.2 ShiftManagementPresenter

```php
class ShiftManagementPresenter {
    public function getShifts(array $filters): array;
    public function createShift(array $data): Shift;
    public function updateShift(string $shiftId, array $data): Shift;
    public function deleteShift(string $shiftId): bool;
    public function assignEmployee(string $shiftId, string $empId): Shift;
    public function getShiftTemplates(): array;
}
```

### 6.3 CoveragePresenter

```php
class CoveragePresenter {
    public function getCoverageReport(string $deptId, \DateTime $date): array;
    public function getCoverageGaps(string $deptId, \DateTime $date): array;
    public function getCoverageAlerts(): array;
    public function getMinimumCoverage(): array;
}
```

### 6.4 TimeClockPresenter

```php
class TimeClockPresenter {
    public function getTimeEntries(string $empId, \DateTime $date): array;
    public function punchIn(string $empId): TimeEntry;
    public function punchOut(string $entryId): TimeEntry;
    public function getWeeklyHours(string $empId, \DateTime $week): array;
    public function getOvertimeStatus(string $empId): array;
}
```

---

## 7. AJAX Handler

### 7.1 Handler Actions

| Action | Method | Description |
|--------|--------|-------------|
| ros_week | handleWeekView | Weekly roster view |
| ros_day | handleDayView | Daily view |
| ros_shift_create | handleShiftCreate | Create shift |
| ros_shift_update | handleShiftUpdate | Update shift |
| ros_shift_delete | handleShiftDelete | Remove shift |
| ros_shift_assign | handleShiftAssign | Assign employee |
| ros_publish | handlePublish | Publish roster |
| ros_swap_request | handleSwapRequest | Request swap |
| ros_swap_approve | handleSwapApprove | Approve swap |
| ros_swap_reject | handleSwapReject | Reject swap |
| ros_punch_in | handlePunchIn | Clock in |
| ros_punch_out | handlePunchOut | Clock out |
| ros_coverage | handleCoverage | Coverage report |

---

## 8. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Roster Calendar | `pages/roster_calendar.php` | Main calendar view |
| Shift Management | `pages/shift_management.php` | CRUD shifts |
| Coverage Report | `pages/coverage_report.php` | Coverage analysis |
| Time Clock | `pages/time_clock.php` | Punch in/out |
| Swap Requests | `pages/swap_requests.php` | Swap management |
| Overtime | `pages/overtime_requests.php` | OT approvals |

---

## 9. Integration Points

### 9.1 With Business Logic
```php
$service = container()->get(RosterServiceInterface::class);
```

### 9.2 With ksf_Calendar
```php
// Shift events for calendar
$calendarService = container()->get(CalendarServiceInterface::class);
```

### 9.3 With ksf_TravelExpense
```php
// Travel claims for roaming staff
$travelService = container()->get(TravelExpenseServiceInterface::class);
```

### 9.4 With Payroll
```php
// Time clock for payroll
$payrollService = container()->get(PayrollServiceInterface::class);
```

---

## 10. Error Handling

| Error Type | FA Handler Response |
|------------|---------------------|
| Validation Error | inline_errors() |
| Not Found | display_error() |
| Permission Denied | display_access_denied() |
| Coverage Violation | alert() |
| Duplicate Punch | error() |

---

## 11. Database Schema

```sql
-- Roster
CREATE TABLE IF NOT EXISTS `{TB_PREF}roster` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `department_id` INT(11) NOT NULL,
    `week_start` DATE NOT NULL,
    `status` VARCHAR(20) DEFAULT 'draft',
    `published_at` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_dept_week` (`department_id`, `week_start`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Shift types
CREATE TABLE IF NOT EXISTS `{TB_PREF}shift_type` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `break_minutes` INT(11) DEFAULT 30,
    `color_code` VARCHAR(7) DEFAULT '#3498db',
    `is_active` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Shifts
CREATE TABLE IF NOT EXISTS `{TB_PREF}roster_shift` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `roster_id` INT(11) NOT NULL,
    `employee_id` INT(11) NOT NULL,
    `shift_type_id` INT(11) DEFAULT NULL,
    `shift_date` DATE NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `break_minutes` INT(11) DEFAULT 30,
    `status` VARCHAR(20) DEFAULT 'assigned',
    `swap_request_id` INT(11) DEFAULT NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_roster` (`roster_id`),
    KEY `idx_employee` (`employee_id`),
    KEY `idx_date` (`shift_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Swap requests
CREATE TABLE IF NOT EXISTS `{TB_PREF}swap_request` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `requesting_emp_id` INT(11) NOT NULL,
    `target_emp_id` INT(11) DEFAULT NULL,
    `shift_id` INT(11) NOT NULL,
    `target_shift_id` INT(11) DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'pending',
    `reason` TEXT,
    `approved_by` VARCHAR(100) DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Time entries (clock punches)
CREATE TABLE IF NOT EXISTS `{TB_PREF}time_entry` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `employee_id` INT(11) NOT NULL,
    `shift_id` INT(11) DEFAULT NULL,
    `punch_date` DATE NOT NULL,
    `punch_in` DATETIME NOT NULL,
    `punch_out` DATETIME DEFAULT NULL,
    `break_minutes` INT(11) DEFAULT 0,
    `worked_hours` DECIMAL(5,2) DEFAULT 0,
    `is_overtime` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_employee_date` (`employee_id`, `punch_date`),
    KEY `idx_shift` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*