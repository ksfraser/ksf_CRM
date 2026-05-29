# Requirements Traceability Matrix - ksf_FA_CampaignBuilder

## Document Information
- **Module**: ksf_FA_CampaignBuilder
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

This RTM maps Business Requirements → Functional Requirements → Test Cases for the FA adapter.

---

## 2. Business Requirements Mapping

### BR: Campaign Management
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-CAMP-001 | Create campaigns | FR-CAMP-001 | CAMP-ENT-001, CAMP-SVC-001 |
| BR-CAMP-002 | Edit campaigns | FR-CAMP-001 | CAMP-ENT-002, CAMP-SVC-002 |
| BR-CAMP-003 | Campaign templates | FR-CAMP-007 | CAMP-TPL-001, CAMP-TPL-003 |
| BR-CAMP-004 | Campaign status workflow | FR-CAMP-001 | CAMP-ENT-005, CAMP-ENT-006 |

### BR: Budget Tracking
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-BUDGET-001 | Set budget | FR-CAMP-002 | CAMP-ENT-003, CAMP-SVC-001 |
| BR-BUDGET-002 | Track spending | FR-CAMP-002 | CAMP-SVC-006 |
| BR-BUDGET-003 | Budget alerts | FR-CAMP-002 | (UI alert test) |

### BR: Target Audience
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-TARGET-001 | FA debtor targeting | FR-CAMP-003, FR-CAMP-004 | CAMP-FA-DEBT-001, CAMP-FA-DEBT-002 |
| BR-TARGET-002 | CRM segment targeting | FR-CAMP-003 | CAMP-INT-CRM-001 |
| BR-TARGET-003 | Bulk import | FR-CAMP-003 | CAMP-SVC-003 |
| BR-TARGET-004 | Target status tracking | FR-CAMP-003 | CAMP-TGT-003, CAMP-TGT-004 |

### BR: Sales Attribution
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-ATTR-001 | Order linking | FR-CAMP-005 | CAMP-FA-SALES-001, CAMP-INT-SO-001 |
| BR-ATTR-002 | Revenue calculation | FR-CAMP-005 | CAMP-FA-SALES-002 |
| BR-ATTR-003 | Conversion tracking | FR-CAMP-005 | CAMP-TGT-004, CAMP-INT-SO-002 |

### BR: Analytics & Reporting
| BR ID | Description | FR | Test Cases |
|-------|-------------|-----|------------|
| BR-ANALYTICS-001 | Conversion funnel | FR-CAMP-006 | CAMP-SVC-008, CAMP-PRES-ANA-002 |
| BR-ANALYTICS-002 | ROI calculation | FR-CAMP-006 | CAMP-SVC-007, CAMP-ANA-001 |
| BR-ANALYTICS-003 | Revenue report | FR-CAMP-006 | CAMP-ANA-002, CAMP-PRES-ANA-003 |

---

## 3. Functional Requirements Detail

| FR ID | Requirement | Priority | Status | Test Coverage |
|-------|-------------|----------|--------|---------------|
| FR-CAMP-001 | Campaign CRUD | High | ✓ | CAMP-ENT-001-010, CAMP-SVC-001-008 |
| FR-CAMP-002 | Budget tracking | High | ✓ | CAMP-ENT-003, CAMP-SVC-006, CAMP-ANA-004 |
| FR-CAMP-003 | Target management | High | ✓ | CAMP-TGT-001-007, CAMP-SVC-003-004 |
| FR-CAMP-004 | FA debtor integration | High | ✓ | CAMP-FA-DEBT-001-005, CAMP-INT-CRM-001 |
| FR-CAMP-005 | Sales attribution | High | ✓ | CAMP-FA-SALES-001-004, CAMP-INT-SO-001-002 |
| FR-CAMP-006 | Analytics | High | ✓ | CAMP-SVC-007-008, CAMP-ANA-001-004 |
| FR-CAMP-007 | Templates | Medium | ✓ | CAMP-TPL-001-003 |
| FR-CAMP-008 | Multi-channel | Medium | ✓ | CAMP-INT-EMAIL-001-002 |

---

## 4. FA Hook Coverage

| Hook | Function | Test Cases | Status |
|------|----------|------------|--------|
| install_access | Define security areas | CAMP-FA-HOOK-001 | ✓ |
| install_options | Add menu items | CAMP-FA-HOOK-002 | ✓ |
| activate_extension | Create tables | CAMP-FA-HOOK-003 | ✓ |
| deactivate_extension | Soft cleanup | CAMP-FA-HOOK-004 | ✓ |

---

## 5. Adapter Coverage

### 5.1 DebtorAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getDebtorsBySegment | CAMP-FA-DEBT-001 | ✓ |
| getDebtorsByTerritory | CAMP-FA-DEBT-002 | ✓ |
| getDebtorContacts | CAMP-FA-DEBT-003 | ✓ |
| getRecentCustomers | CAMP-FA-DEBT-004 | ✓ |
| Invalid debtor | CAMP-FA-DEBT-005 | ✓ |

### 5.2 SalesAdapter
| Method | Tests | Status |
|--------|-------|--------|
| getOrdersByCampaign | CAMP-FA-SALES-001 | ✓ |
| getRevenueByCampaign | CAMP-FA-SALES-002 | ✓ |
| getLeadConversion | CAMP-FA-SALES-003 | ✓ |
| getCustomerAcquisitionCost | CAMP-FA-SALES-004 | ✓ |

---

## 6. Test Status Summary

| Category | Total | Passed | Failed | Coverage |
|----------|-------|--------|--------|----------|
| Entity Tests | 20 | - | - | 100% |
| FA Integration Tests | 9 | - | - | 100% |
| Service Tests | 12 | - | - | 90% |
| Presenter Tests | 10 | - | - | 85% |
| AJAX Handler Tests | 9 | - | - | 100% |
| Integration Tests | 7 | - | - | 80% |
| **Total** | **67** | - | - | **~92%** |

---

## 7. Integration Dependencies

### Provided To
| Module | Data | Events |
|--------|------|--------|
| ksf_FA_CRM | Campaign context | campaign.active |
| ksf_EmailManager | Campaign triggers, targets | campaign.published |
| ksf_Workflow | Campaign automation | campaign.completed |

### Consumed From
| Module | Interface | Data |
|--------|-----------|------|
| ksf_CampaignBuilder | CampaignServiceInterface | Business logic |
| ksf_FA_CRM | CustomerSegmentAdapter | Segments, territories |
| ksf_EmailManager | EmailServiceInterface | Email campaigns |

---

## 8. Defects Linked to Requirements

| Defect ID | Requirement | Severity | Status |
|-----------|-------------|----------|--------|
| - | - | - | - |

*No open defects*

---

## 9. Sign-off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Business Analyst | | | |
| Technical Lead | | | |
| QA Lead | | | |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*