# Test Plan - ksf_FA_CampaignBuilder

## Document Information
- **Module**: ksf_FA_CampaignBuilder
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
Test plan for FA adapter module covering campaign management, target integration, and sales attribution.

### 1.2 Scope
- Entity behavior testing
- FA integration testing
- Hook validation
- Database adapter testing
- Analytics validation

---

## 2. Unit Tests

### 2.1 Campaign Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-ENT-001 | Create campaign with required fields | Campaign created with ID |
| CAMP-ENT-002 | Create campaign without name | ValidationException |
| CAMP-ENT-003 | Set campaign budget | Budget set |
| CAMP-ENT-004 | Set campaign type | Type set (email/event/promotion) |
| CAMP-ENT-005 | Set campaign status | Status transitions valid |
| CAMP-ENT-006 | Invalid status transition (active → draft) | ValidationException |
| CAMP-ENT-007 | Get linked debtors | Returns debtor array |
| CAMP-ENT-008 | Get attributed revenue | Returns calculated total |
| CAMP-ENT-009 | Calculate ROI | Returns percentage |
| CAMP-ENT-010 | Duplicate campaign | New campaign with copied data |

### 2.2 CampaignTarget Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-TGT-001 | Create target with debtor_no | Target created |
| CAMP-TGT-002 | Create target without debtor | Target created (contact only) |
| CAMP-TGT-003 | Update target status | Status changed |
| CAMP-TGT-004 | Mark target converted | converted_date set, status = converted |
| CAMP-TGT-005 | Set attributed revenue | Revenue amount set |
| CAMP-TGT-006 | Get debtor data | Returns FA debtor array |
| CAMP-TGT-007 | Get contact data | Returns contact array |

### 2.3 CampaignTemplate Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-TPL-001 | Create template | Template created |
| CAMP-TPL-002 | Apply template to campaign | Campaign populated |
| CAMP-TPL-003 | Duplicate template | New template created |

---

## 3. FA Integration Tests

### 3.1 hooks.php Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-FA-HOOK-001 | install_access returns valid array | Security areas defined |
| CAMP-FA-HOOK-002 | install_options adds menu items | Menu items registered |
| CAMP-FA-HOOK-003 | activate_extension creates tables | Tables created |
| CAMP-FA-HOOK-004 | deactivate_extension cleanup | Tables preserved (soft delete) |

### 3.2 DebtorAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-FA-DEBT-001 | Get debtors by segment | Returns matching debtors |
| CAMP-FA-DEBT-002 | Get debtors by territory | Returns territorial debtors |
| CAMP-FA-DEBT-003 | Get debtor contacts | Returns contact array |
| CAMP-FA-DEBT-004 | Get recent customers | Returns 2024+ debtors |
| CAMP-FA-DEBT-005 | Invalid debtor number | Returns empty array |

### 3.3 SalesAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-FA-SALES-001 | Get orders by campaign | Returns order array |
| CAMP-FA-SALES-002 | Calculate attributed revenue | Returns decimal sum |
| CAMP-FA-SALES-003 | Get lead conversion | Returns conversion stats |
| CAMP-FA-SALES-004 | Calculate CAC | Returns cost per acquisition |

---

## 4. Service Tests

### 4.1 CampaignService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-SVC-001 | Create campaign | Campaign persisted |
| CAMP-SVC-002 | Update campaign | Campaign updated |
| CAMP-SVC-003 | Add targets to campaign | Targets created |
| CAMP-SVC-004 | Remove targets | Targets removed |
| CAMP-SVC-005 | Publish campaign | Status = active |
| CAMP-SVC-006 | Cancel campaign | Status = cancelled |
| CAMP-SVC-007 | Get campaign metrics | Returns analytics array |
| CAMP-SVC-008 | Get conversion funnel | Returns funnel stages |

### 4.2 AnalyticsService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-ANA-001 | Calculate campaign ROI | Returns percentage |
| CAMP-ANA-002 | Get revenue attribution | Returns breakdown |
| CAMP-ANA-003 | Get conversion rate | Returns percentage |
| CAMP-ANA-004 | Get budget utilization | Returns percentage |

---

## 5. Presenter Tests

### 5.1 CampaignListPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-PRES-LIST-001 | Get paginated list | Returns array |
| CAMP-PRES-LIST-002 | Filter by status | Filtered results |
| CAMP-PRES-LIST-003 | Filter by date range | Date filtered |
| CAMP-PRES-LIST-004 | Search by name | Matching campaigns |

### 5.2 CampaignFormPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-PRES-FORM-001 | Get form data (new) | Empty form |
| CAMP-PRES-FORM-002 | Get form data (edit) | Populated form |
| CAMP-PRES-FORM-003 | Get segments for dropdown | Segment list |
| CAMP-PRES-FORM-004 | Get territories for dropdown | Territory list |
| CAMP-PRES-FORM-005 | Save campaign | Campaign created/updated |

### 5.3 AnalyticsPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-PRES-ANA-001 | Get campaign metrics | Metrics array |
| CAMP-PRES-ANA-002 | Get conversion funnel | Funnel stages |
| CAMP-PRES-ANA-003 | Get ROI analysis | ROI data |

---

## 6. AJAX Handler Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-AJAX-001 | campaign_list request | JSON list response |
| CAMP-AJAX-002 | campaign_create request | JSON created ID |
| CAMP-AJAX-003 | campaign_update request | JSON success |
| CAMP-AJAX-004 | campaign_delete request | JSON success |
| CAMP-AJAX-005 | target_add request | JSON with count |
| CAMP-AJAX-006 | target_remove request | JSON success |
| CAMP-AJAX-007 | analytics_data request | JSON with metrics |
| CAMP-AJAX-008 | Invalid request | 400 error |
| CAMP-AJAX-009 | Unauthorized request | 401 error |

---

## 7. Integration Tests

### 7.1 With ksf_CampaignBuilder (Business Logic)

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-INT-BIZ-001 | Service injection | Service available |
| CAMP-INT-BIZ-002 | CRUD operations | Data persisted |

### 7.2 With ksf_FA_CRM

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-INT-CRM-001 | Segment integration | Segments loaded |
| CAMP-INT-CRM-002 | Customer targeting | Debtors matched |

### 7.3 With ksf_EmailManager

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-INT-EMAIL-001 | Campaign trigger | EmailService notified |
| CAMP-INT-EMAIL-002 | Target export | Email list generated |

### 7.4 With Sales Orders

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| CAMP-INT-SO-001 | Order attribution | Order linked |
| CAMP-INT-SO-002 | Revenue calculation | Attributed revenue updated |

---

## 8. Test Data Fixtures

```php
$campaignData = [
    'id' => 1,
    'name' => 'Q2 Product Launch',
    'type' => 'email',
    'status' => 'active',
    'start_date' => '2026-04-01',
    'end_date' => '2026-04-30',
    'budget' => 5000.00,
    'spent' => 2500.00,
    'channel' => 'email',
    'created_by' => 'admin'
];

$targetData = [
    'id' => 1,
    'campaign_id' => 1,
    'debtor_no' => '12345',
    'contact_id' => 100,
    'status' => 'converted',
    'attributed_revenue' => 1500.00
];

$debtorData = [
    'debtor_no' => '12345',
    'name' => 'Acme Corp',
    'customer_type' => 'Enterprise',
    'territory' => 'North'
];
```

---

## 9. Test Execution

```bash
# Run all tests
composer test

# Run unit tests only
./vendor/bin/phpunit tests/Unit/ksf_FA_CampaignBuilder

# Run FA integration tests
./vendor/bin/phpunit tests/Integration/FA

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/ tests/
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*