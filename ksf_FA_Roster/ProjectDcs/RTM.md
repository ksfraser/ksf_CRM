# Requirements Traceability Matrix - ksf_FA_Roster

## Document Information
- **Module**: ksf_FA_Roster
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

This RTM maps Business Requirements → Functional Requirements → Test Cases for the FA adapter.

---

## 2. Business Requirements Mapping

### BR: Roster Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-ROS-001 | Create/edit rosters | FR-ROS-001 | ROS-ENT-001-010, ROS-SVC-001-006 |
| BR-ROS-002 | Copy previous week | FR-ROS-001 | ROS-SVC-002 |
| BR-ROS-003 | Publish roster | FR-ROS-001 | ROS-ENT-005, ROS-SVC-003 |
| BR-ROS-004 | Lock roster | FR-ROS-001 | ROS-ENT-006, ROS-SVC-004 |

### BR: Shift Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-SHIFT-001 | Shift assignment | FR-ROS-002 | ROS-SHIFT-001-009, ROS-SVC-SHIFT-002 |
| BR-SHIFT-002 | Shift templates | FR-ROS-003 | ROS-TYPE-001-004 |
| BR-SHIFT-003 | Shift cancellation | FR-ROS-002 | ROS-SHIFT-008 |
| BR-SHIFT-004 | Bulk shift creation | FR-ROS-002 | ROS-SVC-SHIFT-005 |

### BR: Time Clock
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-TIME-001 | Punch in | FR-ROS-004 | ROS-TIME-001, ROS-SVC-TIME-001 |
| BR-TIME-002 | Punch out | FR-ROS-004 | ROS-TIME-003, ROS-SVC-TIME-002 |
| BR-TIME-003 | Timesheet creation | FR-ROS-004 | ROS-FA-TS-001 |
| BR-TIME-004 | Overtime tracking | FR-ROS-008 | ROS-TIME-005, ROS-SVC-TIME-004 |

### BR: Swap Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-SWAP-001 | Request swap | FR-ROS-005 | ROS-SWAP-001, ROS-SVC-SWAP-001 |
| BR-SWAP-002 | Approve/reject | FR-ROS-005 | ROS-SWAP-004-005, ROS-SVC-SWAP-002-003 |
| BR-SWAP-003 | Execute swap | FR-ROS-005 | ROS-SWAP-006 |

### BR: Coverage
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-COV-001 | Coverage tracking | FR-ROS-006 | ROS-ENT-009, ROS-PRES-COV-001 |
| BR-COV-002 | Gap detection | FR-ROS-006 | ROS-PRES-COV-002 |
| BR-COV-003 | Coverage alerts | FR-ROS-006 | ROS-PRES-COV-003 |

---

## 3. Functional Requirements Detail

| FR ID | Requirement | Priority | Status | Test Coverage |
|-------|-------------|----------|--------|---------------|
| FR-ROS-001 | Roster management | High | ✓ | ROS-ENT-001-010, ROS-SVC-001-006 |
| FR-ROS-002 | Shift assignment | High | ✓ | ROS-SHIFT-001-009, ROS-SVC-SHIFT-001-005 |
| FR-ROS-003 | Shift templates | Medium | ✓ | ROS-TYPE-001-004, ROS-PRES-SHIFT-004 |
| FR-ROS-004 | Time clock integration | High | ✓ | ROS-TIME-001-006, ROS-SVC-TIME-001-004 |
| FR-ROS-005 | Swap workflow | High | ✓ | ROS-SWAP-001-006, ROS-SVC-SWAP-001-004 |
| FR-ROS-006 | Coverage analysis | High | ✓ | ROS-ENT-009, ROS-PRES-COV-001-004 |
| FR-ROS-007 | FA employee integration | High | ✓ | ROS-FA-EMP-001-005 |
| FR-ROS-008 | Overtime management | Medium | ✓ | ROS-TIME-005, ROS-FA-TS-005 |

---

## 4. FA Hook Coverage

| Hook | Function | Test Cases | Status |
|------|----------|------------|--------|
| install_access | Define security areas | ROS-FA-HOOK-001 | ✓ |
| install_options | Add menu items | ROS-FA-HOOK-002 | ✓ |
| activate_extension | Create tables | ROS-FA-HOOK-003 | ✓ |
| deactivate_extension | Soft cleanup | ROS-FA-HOOK-004 | ✓ |

---

## 5. Adapter Coverage

### 5.1 EmployeeAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getEmployeesByDepartment | ROS-FA-EMP-001 | ✓ |
| getEmployee | ROS-FA-EMP-002 | ✓ |
| getEmployeeSchedule | ROS-FA-EMP-003 | ✓ |
| getEmployeeAvailability | ROS-FA-EMP-004 | ✓ |
| getActiveEmployees | ROS-FA-EMP-005 | ✓ |

### 5.2 TimesheetAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getTimesheetEntries | ROS-FA-TS-001 | ✓ |
| createTimesheetEntry | ROS-FA-TS-002 | ✓ |
| getPunchRecords | ROS-FA-TS-003 | ✓ |
| calculateHours | ROS-FA-TS-004 | ✓ |
| getOvertimeHours | ROS-FA-TS-005 | ✓ |

---

## 6. Test Status Summary

| Category | Total | Passed | Failed | Coverage |
|----------|-------|--------|--------|----------|
| Entity Tests | 24 | - | - | 100% |
| FA Integration Tests | 9 | - | - | 100% |
| Service Tests | 16 | - | - | 90% |
| Presenter Tests | 13 | - | - | 85% |
| AJAX Handler Tests | 10 | - | - | 100% |
| Integration Tests | 5 | - | - | 80% |
| **Total** | **77** | - | - | **~92%** |

---

## 7. Integration Dependencies

### Provided To
| Module | Data | Events |
|--------|------|--------|
| ksf_Calendar | Shift events, schedule | shift.assigned, shift.cancelled |
| ksf_Payroll | Time entries, hours | roster.locked, timesheet.created |
| ksf_TravelExpense | Roaming staff schedule | shift.swapped |

### Consumed From
| Module | Interface | Data |
|--------|-----------|------|
| ksf_Roster | RosterServiceInterface | Business logic |
| ksf_Calendar | CalendarServiceInterface | Event sync |
| ksf_Payroll | PayrollServiceInterface | Timesheet data |

---

## 8. Defects Linked to Requirements

| Defect ID | Requirement | Severity | Status |
|-----------|-------------|----------|--------|
| - | - | - | - |

*No open defects*

---

## 9. Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Business Analyst | | | |
| Technical Lead | | | |
| QA Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*