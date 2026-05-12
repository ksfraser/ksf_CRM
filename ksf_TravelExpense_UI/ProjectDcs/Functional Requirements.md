# Functional Requirements - ksf_TravelExpense_UI

## Document Information
- **Module**: ksf_TravelExpense_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Claim Form Components

| Component | Type | Description |
|-----------|------|-------------|
| ClaimHeader | Section | Claim type, date range |
| ExpenseRow | Dynamic Row | Expense line items |
| CategorySelect | Select | Expense category |
| AmountInput | Input | Currency amount |
| DateInput | Date | Expense date |
| ReceiptUploader | Upload | Receipt attachment |
| DescriptionInput | Textarea | Expense description |
| ProjectSelect | Select | Bill to project |
| SaveButton | Button | Save draft |
| SubmitButton | Button | Submit for approval |

### 1.2 Claim List Components

| Component | Type | Description |
|-----------|------|-------------|
| StatusFilter | Filter | Status dropdown |
| DateRangeFilter | Filter | From/to dates |
| ClaimTable | Table | List of claims |
| StatusBadge | Badge | Status indicator |
| ActionMenu | Menu | View, edit, cancel |

### 1.3 Approval Components

| Component | Type | Description |
|-----------|------|-------------|
| ApprovalQueue | List | Pending claims |
| ClaimDetails | Card | Claim summary |
| ApprovalButtons | Buttons | Approve/reject |
| CommentBox | Textarea | Approval notes |
| HistoryTimeline | Timeline | Status history |

---

## 2. AJAX Endpoints

### 2.1 Claims

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `te_claims` | GET | filter | Claim list |
| `te_claim_get` | GET | claim_id | Claim data |
| `te_claim_save` | POST | formData | Saved |
| `te_claim_submit` | POST | claim_id | Submitted |
| `te_claim_cancel` | POST | claim_id | Cancelled |

### 2.2 Receipts

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `te_receipt_upload` | POST | file, claim_id | Uploaded |
| `te_receipt_list` | GET | claim_id | Receipt list |
| `te_receipt_delete` | POST | receipt_id | Deleted |

### 2.3 Approvals

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `te_pending` | GET | approver_id | Pending list |
| `te_approve` | POST | claim_id, notes | Approved |
| `te_reject` | POST | claim_id, reason | Rejected |
| `te_clarify` | POST | claim_id, message | Clarification |

### 2.4 Calculations

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `te_perdiem` | GET | location, days | Per diem amount |
| `te_validate` | POST | claimData | Validation result |
| `te_export` | GET | filter, format | File |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| amount | Positive number | Required |
| expense_date | Within policy | Invalid date |
| category | Required | Required |
| receipt | Required if over limit | Required |
| description | Max 500 chars | Too long |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| claim_type | Valid type | Invalid type |
| employee_id | FK validation | Invalid employee |
| amount | Within policy limit | Over limit |
| currency | Supported currency | Invalid currency |

---

## 4. Integration Requirements

- ksf_Roster: Travel schedules
- ksf_Documents: Receipt storage
- ksf_ProjectManagement: Project billing

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
