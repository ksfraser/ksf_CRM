# UAT Plan - ksf_Training_UI

## Document Information
- **Module**: ksf_Training_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for training management.

### 1.2 Prerequisites
- ksf_Training business logic installed
- FrontAccounting access
- HR Manager or Employee role

---

## 2. UAT Scenarios

### UAT-TRN-001: Browse and Enroll
**Scenario**: Employee browses catalog and enrolls

**Steps**:
1. Navigate to HR > Training Catalog
2. Filter by "Technical Skills"
3. Search for "Python"
4. Click course details
5. Select session
6. Click Enroll

**Expected Results**:
- [ ] Course list displays
- [ ] Filters work
- [ ] Enrollment successful
- [ ] Confirmation shown

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TRN-002: Create Course
**Scenario**: HR creates new training course

**Steps**:
1. Navigate to Course Management
2. Click "New Course"
3. Enter course details
4. Add 3 competencies
5. Create 2 sessions
6. Publish course

**Expected Results**:
- [ ] Form validation works
- [ ] Course created
- [ ] Sessions visible
- [ ] Course in catalog

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TRN-003: Track Attendance
**Scenario**: Instructor marks attendance

**Steps**:
1. Navigate to Attendance
2. Select session
3. Check present for 8 employees
4. Check absent for 2
5. Submit

**Expected Results**:
- [ ] Employee list displays
- [ ] Checkboxes work
- [ ] Saved successfully
- [ ] Completion status updated

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TRN-004: Submit Feedback
**Scenario**: Employee gives training feedback

**Steps**:
1. Navigate to My Training
2. Find completed course
3. Click "Feedback"
4. Rate 4/5
5. Add comments
6. Submit

**Expected Results**:
- [ ] Feedback form displays
- [ ] Rating selectable
- [ ] Submitted successfully
- [ ] HR sees feedback

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TRN-005: Generate Certificate
**Scenario**: HR generates completion certificate

**Steps**:
1. Navigate to Enrollments
2. Filter "Completed"
3. Select enrollment
4. Click "Generate Certificate"
5. Preview
6. Download PDF

**Expected Results**:
- [ ] Certificate preview displays
- [ ] Employee name correct
- [ ] Course details correct
- [ ] PDF downloads

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TRN-006: Competency Gap Analysis
**Scenario**: Manager views competency gaps

**Steps**:
1. Navigate to Competency Gap
2. Select employee
3. View required vs current
4. See training recommendations

**Expected Results**:
- [ ] Gap chart displays
- [ ] Data accurate
- [ ] Recommendations shown

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
