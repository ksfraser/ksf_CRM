# Requirements Traceability Matrix - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

This RTM maps Business Requirements → Functional Requirements → Test Cases for the FA adapter.

---

## 2. Business Requirements Mapping

### BR: Job Description Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-JD-001 | Create/edit job descriptions | FR-JD-001 | JD-ENT-001-002, JD-SVC-001 |
| BR-JD-002 | Link to FA department | FR-JD-005 | JD-ENT-003, JD-FA-DEPT-001 |
| BR-JD-003 | Version tracking | FR-JD-001 | JD-ENT-010 |
| BR-JD-004 | Archive management | FR-JD-001 | JD-ENT-011 |

### BR: Approval Workflow
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-APPROVE-001 | Submit for approval | FR-JD-002 | JD-SVC-003 |
| BR-APPROVE-002 | Approve job description | FR-JD-002 | JD-SVC-004 |
| BR-APPROVE-003 | Reject with reason | FR-JD-002 | JD-SVC-005 |
| BR-APPROVE-004 | Notification system | FR-JD-002 | (UI test) |

### BR: Competency Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-COMP-001 | Competency library | FR-JD-003 | JD-COMP-001-006, JD-SVC-COMP-001 |
| BR-COMP-002 | Proficiency levels | FR-JD-003 | JD-COMP-003-004 |
| BR-COMP-003 | Job-competency assignment | FR-JD-004 | JD-JDC-001-004, JD-ENT-007 |
| BR-COMP-004 | Gap analysis | FR-JD-007 | JD-COMP-005, JD-SVC-COMP-004 |

### BR: Template System
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-TPL-001 | Create templates | FR-JD-006 | JD-TPL-001 |
| BR-TPL-002 | Apply template | FR-JD-006 | JD-TPL-002, JD-SVC-TPL-002 |
| BR-TPL-003 | Template categories | FR-JD-006 | JD-SVC-TPL-001 |

---

## 3. Functional Requirements Detail

| FR ID | Requirement | Priority | Status | Test Coverage |
|-------|-------------|----------|--------|---------------|
| FR-JD-001 | Job description CRUD | High | ✓ | JD-ENT-001-011, JD-SVC-001-008 |
| FR-JD-002 | Approval workflow | High | ✓ | JD-SVC-003-005 |
| FR-JD-003 | Competency management | High | ✓ | JD-COMP-001-006, JD-SVC-COMP-001-003 |
| FR-JD-004 | Job-competency assignment | High | ✓ | JD-JDC-001-004 |
| FR-JD-005 | FA department integration | High | ✓ | JD-FA-DEPT-001-006 |
| FR-JD-006 | Template system | Medium | ✓ | JD-TPL-001-003, JD-SVC-TPL-001-003 |
| FR-JD-007 | Gap analysis | Medium | ✓ | JD-COMP-005, JD-SVC-COMP-004 |
| FR-JD-008 | Search & filter | Medium | ✓ | JD-ENT-009, JD-SVC-008 |

---

## 4. FA Hook Coverage

| Hook | Function | Test Cases | Status |
|------|----------|------------|--------|
| install_access | Define security areas | JD-FA-HOOK-001 | ✓ |
| install_options | Add menu items | JD-FA-HOOK-002 | ✓ |
| activate_extension | Create tables | JD-FA-HOOK-003 | ✓ |
| deactivate_extension | Soft cleanup | JD-FA-HOOK-004 | ✓ |

---

## 5. Adapter Coverage

### 5.1 DepartmentAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getDepartments | JD-FA-DEPT-001 | ✓ |
| getDepartment | JD-FA-DEPT-002 | ✓ |
| getDepartmentHierarchy | JD-FA-DEPT-003 | ✓ |
| getDepartmentHead | JD-FA-DEPT-004 | ✓ |
| getJobsByDepartment | JD-FA-DEPT-005 | ✓ |
| Invalid department | JD-FA-DEPT-006 | ✓ |

### 5.2 EmployeeAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getEmployeesByDepartment | JD-FA-EMP-001 | ✓ |
| getEmployeeCompetencies | JD-FA-EMP-002 | ✓ |
| getCompetencyGap | JD-FA-EMP-003 | ✓ |
| getAssessments | JD-FA-EMP-004 | ✓ |

---

## 6. Test Status Summary

| Category | Total | Passed | Failed | Coverage |
|----------|-------|--------|--------|----------|
| Entity Tests | 22 | - | - | 100% |
| FA Integration Tests | 10 | - | - | 100% |
| Service Tests | 15 | - | - | 90% |
| Presenter Tests | 11 | - | - | 85% |
| AJAX Handler Tests | 9 | - | - | 100% |
| Integration Tests | 6 | - | - | 80% |
| **Total** | **73** | - | - | **~92%** |

---

## 7. Integration Dependencies

### Provided To
| Module | Data | Events |
|--------|------|--------|
| ksf_Training | Required competencies, gaps | jobdescription.competencies_changed |
| ksf_Performance | Job requirements | jobdescription.approved |
| ksf_Recruitment | Position templates | jobdescription.active |

### Consumed From
| Module | Interface | Data |
|--------|-----------|------|
| ksf_JobDescriptions | JobDescriptionServiceInterface | Business logic |
| ksf_Training | TrainingServiceInterface | Training requirements |
| ksf_Performance | PerformanceServiceInterface | Competency assessments |

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