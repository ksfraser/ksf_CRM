# UAT Plan - ksf_Performance_UI

## Document Information
- **Module**: ksf_Performance_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for performance management.

### 1.2 Prerequisites
- ksf_Performance business logic installed
- FrontAccounting access
- HR Manager or Manager role

---

## 2. UAT Scenarios

### UAT-PERF-001: Create Review
**Scenario**: Manager creates performance review

**Steps**:
1. Login to FrontAccounting
2. Navigate to HR > Performance > New Review
3. Select employee
4. Rate competencies (5 items)
5. Assess goals (3 goals)
6. Add overall rating
7. Write comments
8. Submit review

**Expected Results**:
- [ ] Form renders correctly
- [ ] Competencies load from job description
- [ ] Goals show progress
- [ ] Validation works
- [ ] Submit changes status

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-PERF-002: Self-Review
**Scenario**: Employee completes self-review

**Steps**:
1. Navigate to My Performance
2. Open self-review form
3. Self-rate competencies
4. Rate goals
5. Add comments
6. Submit

**Expected Results**:
- [ ] Form accessible
- [ ] Ratings selectable
- [ ] Submitted successfully

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-PERF-003: Goal Tracking
**Scenario**: Employee updates goal progress

**Steps**:
1. Navigate to Goal Tracker
2. View goals
3. Update progress to 75%
4. Add milestone
5. Save

**Expected Results**:
- [ ] Progress saved
- [ ] Timeline updated
- [ ] Manager notified

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-PERF-004: Calibration
**Scenario**: HR runs calibration session

**Steps**:
1. Navigate to Calibration
2. Select review cycle
3. View rating matrix
4. Move outlier rating
5. Add discussion notes
6. Lock ratings

**Expected Results**:
- [ ] Matrix displays correctly
- [ ] Drag-drop works
- [ ] Ratings locked after save

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-PERF-005: Export Report
**Scenario**: HR exports performance report

**Steps**:
1. Navigate to Reports
2. Select "Performance Summary"
3. Choose department and cycle
4. Generate
5. Export to PDF

**Expected Results**:
- [ ] Report generated
- [ ] PDF contains data
- [ ] Formatting correct

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
