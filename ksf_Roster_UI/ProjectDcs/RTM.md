# Requirements Traceability Matrix - ksf_Roster_UI

## Document Information
- **Module**: ksf_Roster_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Requirement Mapping

### UI Components

| FR ID | Requirement | UI Component | Test Cases |
|-------|-------------|--------------|------------|
| FR-ROS-UI-001 | Calendar view | RosterCalendar | ROS-UI-CAL-001-004 |
| FR-ROS-UI-002 | Shift management | ShiftManagement | ROS-UI-SHIFT-001-004 |
| FR-ROS-UI-003 | Coverage | CoveragePresenter | ROS-UI-COV-001-003 |
| FR-ROS-UI-004 | Time clock | TimeClockPresenter | ROS-UI-CLOCK-001-004 |

### AJAX Endpoints

| FR ID | Requirement | Endpoint | Test Cases |
|-------|-------------|----------|------------|
| FR-ROS-AJAX-001 | Week view | ros_week_view | ROS-AJAX-001 |
| FR-ROS-AJAX-002 | Shift CRUD | ros_shift_* | ROS-AJAX-002-003 |
| FR-ROS-AJAX-003 | Publish | ros_publish | ROS-AJAX-004 |
| FR-ROS-AJAX-004 | Swaps | ros_swap_* | ROS-AJAX-005-006 |
| FR-ROS-AJAX-005 | Time clock | ros_punch_* | ROS-AJAX-007 |
| FR-ROS-AJAX-006 | Coverage | ros_coverage | ROS-AJAX-008 |

### Integration

| BR ID | Description | Integration | Test Cases |
|-------|-------------|-------------|------------|
| BR-ROS-001 | Calendar events | ksf_Calendar | ROS-INT-001 |
| BR-ROS-002 | Travel claims | ksf_TravelExpense | ROS-INT-002 |
| BR-ROS-003 | Overtime | ksf_Payroll | ROS-INT-003 |

---

## 2. Test Status Summary

| Category | Total | Passed | Failed | Coverage |
|----------|-------|--------|--------|----------|
| UI Components | 14 | - | - | - |
| AJAX Handlers | 8 | - | - | - |
| Integration | 3 | - | - | - |
| **Total** | **25** | - | - | **-** |

---

## 3. Defects

| Defect ID | Requirement | Severity | Status |
|-----------|-------------|----------|--------|
| - | - | - | - |

---

## 4. Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Business Analyst | | | |
| Technical Lead | | | |
| QA Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
