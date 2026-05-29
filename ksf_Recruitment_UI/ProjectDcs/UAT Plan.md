# UAT Plan - ksf_Recruitment_UI

## Document Information
- **Module**: ksf_Recruitment_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for recruitment management.

### 1.2 Prerequisites
- ksf_Recruitment business logic installed
- FrontAccounting access
- HR Manager or Recruiter role

---

## 2. UAT Scenarios

### UAT-REC-001: Create Job Opening
**Scenario**: HR creates new job opening

**Steps**:
1. Navigate to HR > Job Openings
2. Click "New Opening"
3. Fill form (title, department, location)
4. Link to job description
5. Set hiring manager
6. Save and publish

**Expected Results**:
- [ ] Form validation works
- [ ] Opening appears in list
- [ ] Status shows "Open"

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-002: Search Candidates
**Scenario**: Recruiter searches for candidates

**Steps**:
1. Navigate to Candidates
2. Search "Python developer"
3. Filter by "Applied" stage
4. View results

**Expected Results**:
- [ ] Search returns results
- [ ] Filters applied
- [ ] Cards display correctly

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-003: Pipeline Kanban
**Scenario**: Manager moves candidate through pipeline

**Steps**:
1. Open pipeline for "Python Developer"
2. Drag candidate from "Phone" to "Technical" stage
3. Confirm move
4. View history

**Expected Results**:
- [ ] Drag-drop works
- [ ] Stage updated
- [ ] Activity logged

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-004: Schedule Interview
**Scenario**: Recruiter schedules interview

**Steps**:
1. Click candidate at "Interview" stage
2. Click "Schedule Interview"
3. Select "Technical Interview"
4. Choose interviewers
5. Pick time slot
6. Send invites

**Expected Results**:
- [ ] Calendar event created
- [ ] Invites sent
- [ ] Status updated

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-005: Submit Feedback
**Scenario**: Interviewer submits feedback

**Steps**:
1. Navigate to My Interviews
2. Open completed interview
3. Rate candidate (4/5)
4. Add comments
5. Submit

**Expected Results**:
- [ ] Feedback saved
- [ ] Recommendation visible
- [ ] Recruiter notified

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-006: Generate Offer
**Scenario**: HR generates offer letter

**Steps**:
1. Open candidate at "Offer" stage
2. Click "Generate Offer"
3. Enter salary and start date
4. Preview offer
5. Send to candidate

**Expected Results**:
- [ ] Offer generated
- [ ] Data included
- [ ] Email sent

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

## 3. Integration UAT Scenarios

### UAT-REC-007: Employee Self-Service View via WP_ESS
**Scenario**: Employee views recruitment status via WP_ESS

**Steps**:
1. Login to WP_ESS as current employee
2. Navigate to Recruitment section
3. View open positions
4. Apply for position (if internal posting)
5. Track application status

**Expected Results**:
- [ ] Employee sees only relevant positions
- [ ] Internal application form works
- [ ] Status updates visible
- [ ] Employee sees own applications only

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-008: Manager Approval Workflow
**Scenario**: Hiring Manager approves position requisition

**Steps**:
1. Navigate to pending job openings
2. Review requisition details
3. Click "Approve"
4. Add budget confirmation
5. Submit to HR

**Expected Results**:
- [ ] Approval logged with timestamp
- [ ] HR notified of approval
- [ ] Status updated to "HR Review"
- [ ] Audit trail recorded

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-009: Project Manager Position View
**Scenario**: Project Manager views project-linked positions

**Steps**:
1. Login as Project Manager
2. Navigate to Projects
3. Open project with active recruitment
4. View linked job openings
5. See candidate pipeline for project

**Expected Results**:
- [ ] Project positions visible to PM
- [ ] Candidate count shown per position
- [ ] Pipeline status displayed
- [ ] PM can add comments to requisition

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-010: Family Company Recruitment Access
**Scenario**: Parent company recruiter sees child company positions

**Steps**:
1. Login as parent company recruiter
2. Navigate to Recruitment > All Positions
3. View positions across family structure
4. Filter by subsidiary company
5. Access candidate data for child company

**Expected Results**:
- [ ] Parent sees child company positions (default)
- [ ] Gift-flagged contract positions hidden
- [ ] Filter by subsidiary works correctly
- [ ] Separate subsidiary views accessible

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-REC-011: Contract-Linked Recruitment Visibility
**Scenario**: Contact with contract access views linked job openings

**Steps**:
1. Login as contact with contract visibility
2. Navigate to Recruitment section
3. View positions linked to accessible contracts
4. See candidates for contract positions

**Expected Results**:
- [ ] Only contract-linked positions visible
- [ ] Contract scope filtering works
- [ ] Candidate visibility matches contract
- [ ] Non-contract positions hidden

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

## 4. Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| UAT Lead | | | |
| Technical Lead | | | |

---

## Appendix: Cross-Reference

| Document | Reference |
|----------|-----------|
| Functional Requirements | [Functional Requirements.md](./Functional Requirements.md) |
| Test Plan | [Test Plan.md](./Test Plan.md) |
| RTM | [RTM.md](./RTM.md) |
| Business Requirements | [Business Requirements.md](./Business Requirements.md) |

*Document Version: 1.1.0*
*Last Updated: 2026-05-13*
