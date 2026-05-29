# UAT Plan - ksf_JobDescriptions_UI

## Document Information
- **Module**: ksf_JobDescriptions_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for job description management.

### 1.2 Prerequisites
- ksf_JobDescriptions business logic installed
- FrontAccounting access
- HR Manager role

---

## 2. UAT Scenarios

### UAT-JD-001: Create Job Description
**Scenario**: HR Manager creates new job description

**Steps**:
1. Login to FrontAccounting
2. Navigate to HR > Job Descriptions > New
3. Enter title "Senior Software Engineer"
4. Select "Engineering" department
5. Add responsibilities (3 items)
6. Add qualifications (4 items)
7. Select competencies (5 items)
8. Click Save

**Expected Results**:
- [ ] Form displays without errors
- [ ] Validation prevents empty fields
- [ ] Save redirects to list
- [ ] New record visible in list
- [ ] Success toast displayed

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-002: Edit Job Description
**Scenario**: HR Manager edits existing job description

**Steps**:
1. Navigate to Job Descriptions list
2. Click edit icon on "Software Engineer"
3. Modify title to "Lead Software Engineer"
4. Add 2 more competencies
5. Click Save

**Expected Results**:
- [ ] Form pre-populated with existing data
- [ ] Changes saved successfully
- [ ] Version history shows update

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-003: Search and Filter
**Scenario**: Hiring Manager searches for job descriptions

**Steps**:
1. Navigate to Job Descriptions list
2. Search "software" in search box
3. Filter by Engineering department
4. Filter by Active status

**Expected Results**:
- [ ] Results update in real-time
- [ ] Filters applied correctly
- [ ] Clear filters option works

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-004: View Competency Matrix
**Scenario**: Department Head views competency matrix

**Steps**:
1. Navigate to HR > Competency Matrix
2. Select "Engineering" department
3. View matrix grid
4. Click competency for details

**Expected Results**:
- [ ] Matrix displays competencies vs levels
- [ ] Department filter works
- [ ] Competency details modal opens

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-005: Export Job Description
**Scenario**: HR Manager exports job description to PDF

**Steps**:
1. Open job description detail
2. Click Export > PDF
3. Download and verify content

**Expected Results**:
- [ ] PDF downloads
- [ ] PDF contains all fields
- [ ] Formatting intact

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-006: Competency Autocomplete
**Scenario**: HR Manager adds competencies using autocomplete

**Steps**:
1. Create new job description
2. Click in competency field
3. Type "Java"
4. Select from suggestions
5. Set proficiency level to 4

**Expected Results**:
- [ ] Suggestions appear as typing
- [ ] Selection adds to field
- [ ] Level selector visible

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-JD-007: Mobile Responsive
**Scenario**: User accesses job descriptions on mobile

**Steps**:
1. Access job descriptions on mobile device
2. Navigate to list
3. Try to create new

**Expected Results**:
- [ ] Mobile layout displays
- [ ] Cards replace table
- [ ] Touch navigation works

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
