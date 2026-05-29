# Architecture - ksf_TravelExpense_UI

## Document Information
- **Module**: ksf_TravelExpense_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_TravelExpense_UI provides the FrontAccounting web interface layer for travel and expense management.

### 1.1 Namespace
```php
Ksfraser\FA\TravelExpense\UI\
```

### 1.2 Layer Pattern
```
ksf_TravelExpense_UI/
├── composer.json
├── ProjectDcs/
├── pages/
├── js/
├── src/Ksfraser/FA/TravelExpense/UI/
│   ├── Presenter/
│   │   ├── ClaimFormPresenter.php
│   │   ├── ClaimListPresenter.php
│   │   ├── ApprovalPresenter.php
│   │   └── ReportPresenter.php
│   ├── Component/
│   │   ├── ExpenseForm.php
│   │   ├── ReceiptUploader.php
│   │   ├── CategorySelect.php
│   │   ├── ApprovalWorkflow.php
│   │   ├── PerDiemCalculator.php
│   │   └── PolicyBadge.php
│   └── Handler/
│       └── TravelExpenseAjaxHandler.php
└── templates/
    └── travel_expense/
```

---

## 2. Presenter Layer

### 2.1 ClaimFormPresenter

```php
class ClaimFormPresenter {
    public function getClaimForm(string $claimId = null): array;
    public function getExpenseCategories(): array;
    public function getProjects(): array;
    public function getExpenseTypes(string $categoryId): array;
    public function calculatePerDiem(string $location, int $days): float;
    public function saveClaim(array $data): TravelClaim;
    public function validateClaim(array $data): ValidationResult;
}
```

### 2.2 ClaimListPresenter

```php
class ClaimListPresenter {
    public function getClaims(array $filters): array;
    public function getMyClaims(string $employeeId): array;
    public function getClaimsForApproval(string $approverId): array;
    public function getClaimSummary(string $claimId): array;
    public function getReceipts(string $claimId): array;
}
```

### 2.3 ApprovalPresenter

```php
class ApprovalPresenter {
    public function getPendingApprovals(string $approverId): array;
    public function getClaimDetails(string $claimId): array;
    public function approveClaim(string $claimId, string $notes): bool;
    public function rejectClaim(string $claimId, string $reason): bool;
    public function requestClarification(string $claimId, string $message): bool;
}
```

### 2.4 ReportPresenter

```php
class ReportPresenter {
    public function getExpenseReport(array $filters): array;
    public function getBudgetUsage(string $departmentId): array;
    public function getExpenseByCategory(string $departmentId, \DateTime $period): array;
    public function exportReport(array $filters, string $format): string;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `ExpenseForm` | Expense entry form |
| `ReceiptUploader` | Drag-drop receipt upload |
| `CategorySelect` | Category/category dropdown |
| `ApprovalWorkflow` | Multi-level approval display |
| `PerDiemCalculator` | Per diem calculation widget |
| `PolicyBadge` | Policy compliance indicator |
| `StatusBadge` | Claim status display |
| `CurrencyInput` | Currency amount input |

---

## 4. AJAX Handler Layer

| Action | Method | Description |
|--------|--------|-------------|
| `te_claims` | handleClaims | Claim list |
| `te_claim_get` | handleClaimGet | Get claim |
| `te_claim_save` | handleClaimSave | Create/update |
| `te_receipt_upload` | handleReceiptUpload | Upload receipt |
| `te_receipt_delete` | handleReceiptDelete | Delete receipt |
| `te_approve` | handleApprove | Approve claim |
| `te_reject` | handleReject | Reject claim |
| `te_calculate_perdiem` | handlePerDiem | Calculate per diem |
| `te_validate` | handleValidate | Policy validation |
| `te_export` | handleExport | Export report |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Claim Form | `pages/expense_claim.php` | New/edit claim |
| My Claims | `pages/my_claims.php` | Employee list |
| Approvals | `pages/approvals.php` | Pending approvals |
| Receipts | `pages/receipts.php` | Receipt management |
| Reports | `pages/expense_reports.php` | Analytics |
| Policy | `pages/expense_policy.php` | Policy viewer |

---

## 6. Integration Points

### 6.1 With ksf_Roster
```php
// Get travel schedules
$rosterService = container()->get(RosterServiceInterface::class);
```

### 6.2 With ksf_Documents
```php
// Store receipts
$documentService = container()->get(DocumentServiceInterface::class);
```

### 6.3 With ksf_ProjectManagement
```php
// Bill to projects
$projectService = container()->get(ProjectServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
