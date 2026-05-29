# UAT Plan - ksf_TravelExpense_UI

## Document Information
- **Module**: ksf_TravelExpense_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UAT Overview

### 1.1 Purpose
Validate UI adapter functionality for travel and expense management.

### 1.2 Prerequisites
- ksf_TravelExpense business logic installed
- FrontAccounting access
- Employee, Manager, or Finance role

---

## 2. UAT Scenarios

### UAT-TE-001: Create Expense Claim
**Scenario**: Employee creates travel expense claim

**Steps**:
1. Navigate to Expenses > New Claim
2. Select "Travel" type
3. Add expense: Hotel $150
4. Upload receipt
5. Add expense: Meals $80
6. Calculate per diem for 3 days
7. Submit claim

**Expected Results**:
- [ ] Form displays correctly
- [ ] Receipt upload works
- [ ] Per diem calculated
- [ ] Claim submitted
- [ ] Status updated

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TE-002: Upload Receipt
**Scenario**: Employee uploads receipt for expense

**Steps**:
1. Open expense claim
2. Click upload on expense row
3. Drag-drop PDF file
4. Verify upload success
5. View receipt thumbnail

**Expected Results**:
- [ ] File upload succeeds
- [ ] File type validated
- [ ] Receipt linked
- [ ] Preview available

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TE-003: Approve Expense
**Scenario**: Manager approves expense claim

**Steps**:
1. Navigate to Expense Approvals
2. View pending claims
3. Click claim for details
4. Review expenses
5. Click Approve
6. Add approval notes
7. Submit

**Expected Results**:
- [ ] Claim details display
- [ ] Receipts viewable
- [ ] Approval works
- [ ] Status updated
- [ ] Employee notified

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TE-004: Per Diem Calculator
**Scenario**: Employee calculates per diem

**Steps**:
1. Create new travel claim
2. Select destination "London"
3. Enter dates (Mon-Fri)
4. Click Calculate
5. View per diem amount
6. Adjust for actuals

**Expected Results**:
- [ ] Calculator widget loads
- [ ] Per diem calculated
- [ ] Amount added to claim
- [ ] Policy compliance shown

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TE-005: View Expense Report
**Scenario**: Finance runs expense report

**Steps**:
1. Navigate to Expense Reports
2. Select Q1 2026
3. Select Engineering department
4. Generate report
5. View by category
6. Export to Excel

**Expected Results**:
- [ ] Report generates
- [ ] Data accurate
- [ ] Categories breakdown
- [ ] Export works

**Status**: ☐ Pass  ☐ Fail  ☐ N/A

---

### UAT-TE-006: Reject Expense
**Scenario**: Manager rejects expense claim

**Steps**:
1. Open pending claim
2. Review expenses
3. Find non-compliant item
4. Click Reject
5. Enter reason
6. Submit

**Expected Results**:
- [ ] Rejection modal opens
- [ ] Reason required
- [ ] Claim rejected
- [ ] Employee notified
- [ ] Claim status updated

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
