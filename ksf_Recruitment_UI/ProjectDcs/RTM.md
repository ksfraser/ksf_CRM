# Requirements Traceability Matrix - ksf_Recruitment_UI

## Document Information
- **Module**: ksf_Recruitment_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Requirement Mapping

### UI Components

| FR ID | Requirement | UI Component | Test Cases |
|-------|-------------|--------------|------------|
| FR-REC-UI-001 | Job opening list | JobOpeningList | REC-UI-JOB-001-004 |
| FR-REC-UI-002 | Candidate list | CandidateList | REC-UI-CAND-001-004 |
| FR-REC-UI-003 | Pipeline view | Pipeline | REC-UI-PIPE-001-004 |
| FR-REC-UI-004 | Candidate profile | CandidateProfile | REC-UI-PROF-001-004 |

### AJAX Endpoints

| FR ID | Requirement | Endpoint | Test Cases |
|-------|-------------|----------|------------|
| FR-REC-AJAX-001 | Jobs CRUD | rec_job_* | REC-AJAX-001-002 |
| FR-REC-AJAX-002 | Candidates | rec_candidates | REC-AJAX-003-004 |
| FR-REC-AJAX-003 | Pipeline | rec_pipeline, rec_move | REC-AJAX-005-006 |
| FR-REC-AJAX-004 | Interviews | rec_schedule_interview | REC-AJAX-007 |
| FR-REC-AJAX-005 | Offers | rec_offers | REC-AJAX-008 |

### Integration

| BR ID | Description | Integration | Test Cases |
|-------|-------------|-------------|------------|
| BR-REC-001 | Job requirements | ksf_JobDescriptions | REC-INT-001 |
| BR-REC-002 | Scheduling | ksf_Calendar | REC-INT-002 |
| BR-REC-003 | Documents | ksf_Documents | REC-INT-003 |
| BR-REC-004 | Notifications | ksf_EmailManager | REC-INT-004 |

---

## 2. Test Status Summary

| Category | Total | Passed | Failed | Coverage |
|----------|-------|--------|--------|----------|
| UI Components | 16 | - | - | - |
| AJAX Handlers | 8 | - | - | - |
| Integration | 4 | - | - | - |
| **Total** | **28** | - | - | **-** |

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
