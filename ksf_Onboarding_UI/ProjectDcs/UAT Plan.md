# UAT Plan - ksf_Onboarding_UI

## Document Information
- **Module**: ksf_Onboarding_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for employee onboarding.

### 1.2 Prerequisites
- ksf_Onboarding business logic installed
- FrontAccounting access
- HR Manager role

---

## 2. UAT Scenarios

### UAT-ONB-001: View Dashboard
**Scenario**: HR Manager views onboarding dashboard

**Steps**:
1. Login to FrontAccounting
2. Navigate to HR > Onboarding Dashboard
3. View summary cards
4. View active onboardings

**Expected Results**:
- [ ] Summary displays correctly
- [ ] Active hires list shows
- [ ] Progress rings accurate

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ONB-002: Start New Hire Onboarding
**Scenario**: HR starts onboarding for new hire

**Steps**:
1. Navigate to New Hire Onboarding
2. Select employee
3. Complete wizard steps
4. Verify tasks created

**Expected Results**:
- [ ] Wizard navigates correctly
- [ ] Tasks auto-created
- [ ] Timeline shows new hire

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ONB-003: Complete Task
**Scenario**: Manager completes onboarding task

**Steps**:
1. Navigate to My Onboarding Tasks
2. Click on task
3. Mark checklist items
4. Submit completion

**Expected Results**:
- [ ] Task marked complete
- [ ] Progress updated
- [ ] Notification sent

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ONB-004: Upload Document
**Scenario**: New hire uploads required document

**Steps**:
1. Navigate to Documents
2. Drag-drop PDF file
3. Select document type
4. Submit upload

**Expected Results**:
- [ ] File uploads
- [ ] Validation works
- [ ] Document appears in list

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-ONB-005: Assign Task
**Scenario**: HR assigns task to manager

**Steps**:
1. Open pending task
2. Click Assign
3. Select manager
4. Set due date
5. Confirm

**Expected Results**:
- [ ] Assignment saved
- [ ] Notification sent to assignee
- [ ] Due date displayed

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

## 3. Sign-Off

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Business Owner | | | |
| UAT Lead | | | |
| Technical Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
