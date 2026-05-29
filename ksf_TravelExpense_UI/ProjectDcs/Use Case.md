# Use Cases - ksf_TravelExpense_UI

---

## UC-TE-001: Create Expense Claim
**Actor**: Employee

**Preconditions**: User has expense access

**Flow**:
1. Navigate to Expenses > New Claim
2. Select claim type (Travel/Misc)
3. Add expense rows:
   - Select category
   - Enter amount
   - Select date
   - Upload receipt
   - Add description
4. Link to project (if applicable)
5. Submit claim

---

## UC-TE-002: Upload Receipt
**Actor**: Employee

**Preconditions**: Claim exists, expense item added

**Flow**:
1. Open expense claim
2. Click upload icon on expense row
3. Drag-drop or select file
4. System validates file type/size
5. Receipt linked to expense
6. Preview available

---

## UC-TE-003: View My Claims
**Actor**: Employee

**Preconditions**: Claims submitted

**Flow**:
1. Navigate to My Expenses
2. View list of claims
3. Filter by status (Draft, Submitted, Approved, Paid)
4. Click claim for details
5. View receipt attachments

---

## UC-TE-004: Approve Expense Claim
**Actor**: Manager, Finance

**Preconditions**: Claim pending approval

**Flow**:
1. Navigate to Expense Approvals
2. View pending claims
3. Click claim for details
4. Review expenses and receipts
5. Check policy compliance
6. Approve or reject with reason
7. If approved, sent to finance

---

## UC-TE-005: Calculate Per Diem
**Actor**: Employee

**Preconditions**: Travel location selected

**Flow**:
1. Open new travel claim
2. Select destination
3. Enter trip dates
4. System calculates per diem
5. Per diem added to claim
6. Employee adjusts actuals

---

## UC-TE-006: View Expense Report
**Actor**: Manager, Finance

**Preconditions**: Access to reports

**Flow**:
1. Navigate to Expense Reports
2. Select date range
3. Select department
4. Generate report
5. View by category, employee, project
6. Export to PDF/Excel

---

## UC-TE-007: Cancel Expense Claim
**Actor**: Employee

**Preconditions**: Claim in Draft or Submitted status

**Flow**:
1. Open My Claims
2. Select draft/submitted claim
3. Click Cancel
4. Confirm cancellation
5. Claim marked cancelled
6. Receipts retained for audit

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
