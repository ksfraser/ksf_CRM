# Architecture - ksf_Roster_UI

## Document Information
- **Module**: ksf_Roster_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_Roster_UI provides the FrontAccounting web interface layer for employee roster and scheduling.

### 1.1 Namespace
```php
Ksfraser\FA\Roster\UI\
```

### 1.2 Layer Pattern
```
ksf_Roster_UI/
├── composer.json
├── ProjectDcs/
├── pages/
├── js/
├── src/Ksfraser/FA/Roster/UI/
│   ├── Presenter/
│   │   ├── RosterCalendarPresenter.php
│   │   ├── ShiftManagementPresenter.php
│   │   ├── CoveragePresenter.php
│   │   └── TimeClockPresenter.php
│   ├── Component/
│   │   ├── RosterGrid.php
│   │   ├── ShiftCard.php
│   │   ├── TimeClockWidget.php
│   │   ├── CoverageIndicator.php
│   │   └── SwapRequestCard.php
│   └── Handler/
│       └── RosterAjaxHandler.php
└── templates/
    └── roster/
```

---

## 2. Presenter Layer

### 2.1 RosterCalendarPresenter

```php
class RosterCalendarPresenter {
    public function getWeekView(string $departmentId, \DateTime $week): array;
    public function getDayView(string $departmentId, \DateTime $date): array;
    public function publishRoster(string $departmentId, \DateTime $week): bool;
    public function copyPreviousWeek(string $departmentId, \DateTime $week): bool;
}
```

### 2.2 ShiftManagementPresenter

```php
class ShiftManagementPresenter {
    public function getShifts(array $filters): array;
    public function getShiftDetails(string $shiftId): array;
    public function createShift(array $data): Shift;
    public function updateShift(string $shiftId, array $data): Shift;
    public function getShiftTemplates(): array;
}
```

### 2.3 CoveragePresenter

```php
class CoveragePresenter {
    public function getCoverageReport(string $departmentId, \DateTime $date): array;
    public function getCoverageGaps(string $departmentId, \DateTime $date): array;
    public function getCoverageAlerts(): array;
}
```

### 2.4 TimeClockPresenter

```php
class TimeClockPresenter {
    public function getTimeEntries(string $employeeId, \DateTime $date): array;
    public function punchIn(string $employeeId): TimeEntry;
    public function punchOut(string $entryId): TimeEntry;
    public function getWeeklyHours(string $employeeId, \DateTime $week): array;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `RosterGrid` | Weekly calendar grid |
| `ShiftCard` | Shift block on grid |
| `TimeClockWidget` | Punch in/out widget |
| `CoverageIndicator` | Coverage percentage |
| `SwapRequestCard` | Swap request display |
| `OvertimeAlert` | Overtime warning |
| `EmployeeChip` | Draggable employee |
| `ShiftTemplate` | Quick shift creation |

---

## 4. AJAX Handler Layer

| Action | Method | Description |
|--------|--------|-------------|
| `ros_week` | handleWeekView | Weekly view |
| `ros_shift_create` | handleShiftCreate | Create shift |
| `ros_shift_update` | handleShiftUpdate | Update shift |
| `ros_shift_delete` | handleShiftDelete | Delete shift |
| `ros_publish` | handlePublish | Publish roster |
| `ros_swap_request` | handleSwapRequest | Request swap |
| `ros_swap_approve` | handleSwapApprove | Approve swap |
| `ros_punch_in` | handlePunchIn | Clock in |
| `ros_punch_out` | handlePunchOut | Clock out |
| `ros_coverage` | handleCoverage | Coverage report |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Roster Calendar | `pages/roster_calendar.php` | Main calendar |
| Shift Management | `pages/shift_management.php` | CRUD shifts |
| Coverage Report | `pages/coverage_report.php` | Coverage analysis |
| Time Clock | `pages/time_clock.php` | Punch in/out |
| Swap Requests | `pages/swap_requests.php` | Swap management |
| Overtime | `pages/overtime_requests.php` | OT approvals |

---

## 6. Integration Points

### 6.1 With ksf_Calendar
```php
// Create shift events
$calendarService = container()->get(CalendarServiceInterface::class);
```

### 6.2 With ksf_TravelExpense
```php
// Travel claims for rostering
$travelService = container()->get(TravelExpenseServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
