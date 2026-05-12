# UAT Plan - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate FrontAccounting adapter functionality for job description management.

### 1.2 Prerequisites
- FrontAccounting installed
- ksf_JobDescriptions business logic installed
- ksf_Training installed (competency gap)
- ksf_Performance installed (assessments)
- Test FA company with departments

### 1.3 Test Users
- HR Manager (SA_JOB_DESC_CREATE)
- Department Head (SA_JOB_DESC_APPROVE)
- HR Admin (SA_JOB_DESC_ADMIN)

---

## 2. UAT Scenarios

### UAT-JD-001: Create Job Description
**Scenario**: HR Manager creates new job description

**Steps**:
1. Login to FrontAccounting
2. Navigate to HRM > Job Descriptions
3. Click "New Job Description"
4. Enter title: "Lead Developer"
5. Select department: "Engineering"
6. Add description: "Leads technical development team..."
7. Add responsibilities (3 items)
8. Add qualifications (2 items)
9. Add 4 competencies with levels
10. Save

**Expected Results**:
- [ ] Job description created
- [ ] Appears in list with Draft status
- [ ] Department linked correctly
- [ ] Competencies displayed

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-002: Use Job Template
**Scenario**: HR Manager uses template for new position

**Steps**:
1. Create new job description
2. Click "Use Template"
3. Select "Developer Standard Template"
4. Template populates:
   - Standard responsibilities
   - Common qualifications
   - Suggested competencies
5. Modify for specific role
6. Save

**Expected Results**:
- [ ] Template fields auto-populated
- [ ] Modifications allowed
- [ ] Job description created

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-003: Submit for Approval
**Scenario**: HR Manager submits job description for approval

**Steps**:
1. Open draft job description
2. Click "Submit for Approval"
3. Confirm submission
4. Verify status changes

**Expected Results**:
- [ ] Status changes to "Pending Approval"
- [ ] Notification sent to department head
- [ ] Approval option visible to approver

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-004: Approve Job Description
**Scenario**: Department Head approves job description

**Steps**:
1. Login as department head
2. Receive notification of pending job description
3. Review job description details
4. Click "Approve"
5. Add comment: "Looks good"
6. Confirm

**Expected Results**:
- [ ] Status changes to "Active"
- [ ] Approved_by and date recorded
- [ ] HR Manager notified
- [ ] Job description visible in active list

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-005: Reject Job Description
**Scenario**: Department Head rejects job description

**Steps**:
1. Review pending job description
2. Identify issue: "Missing cloud competency"
3. Click "Reject"
4. Enter reason: "Please add cloud architecture competency at level 4"
5. Confirm

**Expected Results**:
- [ ] Status returns to "Draft"
- [ ] Rejection reason displayed
3. [ ] HR Manager notified with reason

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-006: Add Competencies
**Scenario**: HR Manager adds required competencies

**Steps**:
1. Open job description in edit mode
2. Click "Add Competency"
3. Search "Python"
4. Select from results: "Python Programming"
5. Set required level: 4
6. Set importance: "Required"
7. Add notes: "Must include Django"
8. Save

**Expected Results**:
- [ ] Competency added to list
- [ ] Level and importance displayed
- [ ] Saved successfully

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-007: View Competency Matrix
**Scenario**: HR Manager views department competency matrix

**Steps**:
1. Navigate to HRM > Competency Matrix
2. Filter by department: "Engineering"
3. View matrix grid
4. Click competency header for details
5. Export to Excel

**Expected Results**:
- [ ] Matrix displays jobs vs competencies
- [ ] Required levels shown in cells
- [ ] Filter works correctly
- [ ] Export generates valid file

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-008: Competency Gap Analysis
**Scenario**: Manager views employee competency gap

**Steps**:
1. Open job description "Lead Developer"
2. Click "Gap Analysis"
3. Select employee: "John Smith"
4. System displays:
   - Required competencies with levels
   - Employee's current levels
   - Gaps highlighted

**Expected Results**:
- [ ] Gap analysis calculates correctly
- [ ] Visual chart displays
- [ ] Training recommendations shown

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-009: Archive Job Description
**Scenario**: HR Manager archives obsolete job description

**Steps**:
1. Open job description
2. Click "Archive"
3. Confirm archive action

**Expected Results**:
- [ ] Status changes to "Archived"
- [ ] Removed from active list
- [ ] Historical data preserved
- [ ] Version history maintained

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-010: Create Competency
**Scenario**: HR Admin creates new competency

**Steps**:
1. Navigate to Setup > Competency Admin
2. Click "Add Competency"
3. Enter name: "Cloud Architecture"
4. Select category: "Technical"
5. Add description
6. Define proficiency levels 1-5
7. Save

**Expected Results**:
- [ ] Competency created
- [ ] Available in competency selector
- [ ] Levels stored correctly

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-011: Search Job Descriptions
**Scenario**: Hiring Manager searches for positions

**Steps**:
1. Navigate to Job Descriptions
2. Enter search: "Developer"
3. Apply filter: Department = Engineering
4. Apply filter: Status = Active

**Expected Results**:
- [ ] Results filter in real-time
- [ ] All filters applied correctly
- [ ] Clear filters works

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-012: View Version History
**Scenario**: HR Manager views job description history

**Steps**:
1. Open job description
2. Click "Version History"
3. View list of versions
4. Click previous version to compare

**Expected Results**:
- [ ] Version list displays
- [ ] Version details accessible
- [ ] Comparison view works

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