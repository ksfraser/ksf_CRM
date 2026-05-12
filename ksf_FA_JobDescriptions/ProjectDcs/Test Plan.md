# Test Plan - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
Test plan for FA adapter module covering job description management, competency integration, and approval workflow.

### 1.2 Scope
- Entity behavior testing
- FA integration testing
- Hook validation
- Database adapter testing
- Workflow validation

---

## 2. Unit Tests

### 2.1 JobDescription Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-ENT-001 | Create job description with required fields | JD created with ID |
| JD-ENT-002 | Create JD without title | ValidationException |
| JD-ENT-003 | Set department | Department linked |
| JD-ENT-004 | Set status workflow | Status transitions valid |
| JD-ENT-005 | Invalid transition (archived → draft) | ValidationException |
| JD-ENT-006 | Add responsibility | Responsibility added |
| JD-ENT-007 | Add competency requirement | Competency linked |
| JD-ENT-008 | Get department data | Returns FA department array |
| JD-ENT-009 | Get linked employees | Returns employee array |
| JD-ENT-010 | Version increment | Version updated |
| JD-ENT-011 | Archive job description | Status = archived |

### 2.2 Competency Entity Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-COMP-001 | Create competency | Competency created |
| JD-COMP-002 | Create competency without name | ValidationException |
| JD-COMP-003 | Set proficiency levels | Levels stored |
| JD-COMP-004 | Get proficiency description | Returns level text |
| JD-COMP-005 | Get employee level | Returns level for employee |
| JD-COMP-006 | Inactivate competency | is_active = false |

### 2.3 JobDescriptionCompetency Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-JDC-001 | Link competency to JD | Link created |
| JD-JDC-002 | Set required level (1-5) | Level set |
| JD-JDC-003 | Set importance (required/preferred) | Importance set |
| JD-JDC-004 | Update requirement | Requirement updated |

### 2.4 Template Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-TPL-001 | Create template | Template created |
| JD-TPL-002 | Apply template to JD | JD populated |
| JD-TPL-003 | Clone template | New template created |

---

## 3. FA Integration Tests

### 3.1 hooks.php Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-FA-HOOK-001 | install_access returns valid array | Security areas defined |
| JD-FA-HOOK-002 | install_options adds menu items | Menu items in HRM |
| JD-FA-HOOK-003 | activate_extension creates tables | Tables created |
| JD-FA-HOOK-004 | deactivate_extension cleanup | Tables preserved |

### 3.2 DepartmentAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-FA-DEPT-001 | Get all departments | Returns FA dimensions |
| JD-FA-DEPT-002 | Get single department | Returns dimension data |
| JD-FA-DEPT-003 | Get department hierarchy | Returns tree structure |
| JD-FA-DEPT-004 | Get department head | Returns user ID |
| JD-FA-DEPT-005 | Get jobs by department | Returns job array |
| JD-FA-DEPT-006 | Invalid department ID | Returns null |

### 3.3 EmployeeAdapter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-FA-EMP-001 | Get employees by department | Returns employee array |
| JD-FA-EMP-002 | Get employee competencies | Returns competency levels |
| JD-FA-EMP-003 | Get competency gap | Returns gap calculation |
| JD-FA-EMP-004 | Get assessments | Returns review history |

---

## 4. Service Tests

### 4.1 JobDescriptionService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-SVC-001 | Create job description | JD persisted |
| JD-SVC-002 | Update job description | JD updated, version incremented |
| JD-SVC-003 | Submit for approval | Status = pending |
| JD-SVC-004 | Approve job description | Status = active |
| JD-SVC-005 | Reject job description | Status = draft, reason stored |
| JD-SVC-006 | Archive job description | Status = archived |
| JD-SVC-007 | Get job descriptions (filtered) | Returns filtered array |
| JD-SVC-008 | Search job descriptions | Returns search results |

### 4.2 CompetencyService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-SVC-COMP-001 | Create competency | Competency persisted |
| JD-SVC-COMP-002 | Update competency | Competency updated |
| JD-SVC-COMP-003 | Search competencies | Returns matching |
| JD-SVC-COMP-004 | Get competency gap | Returns gap analysis |

### 4.3 TemplateService Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-SVC-TPL-001 | Create template | Template persisted |
| JD-SVC-TPL-002 | Apply template | JD populated |
| JD-SVC-TPL-003 | Get templates | Returns template list |

---

## 5. Presenter Tests

### 5.1 JobDescriptionListPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-PRES-LIST-001 | Get paginated list | Returns array |
| JD-PRES-LIST-002 | Filter by department | Filtered results |
| JD-PRES-LIST-003 | Filter by status | Filtered results |
| JD-PRES-LIST-004 | Search by title | Matching JDs |
| JD-PRES-LIST-005 | Get statistics | Returns stats |

### 5.2 JobDescriptionFormPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-PRES-FORM-001 | Get form data (new) | Empty form |
| JD-PRES-FORM-002 | Get form data (edit) | Populated form |
| JD-PRES-FORM-003 | Get departments | Department list |
| JD-PRES-FORM-004 | Get competencies | Competency list |
| JD-PRES-FORM-005 | Get templates | Template list |
| JD-PRES-FORM-006 | Save job description | JD created/updated |

### 5.3 CompetencyMatrixPresenter Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-PRES-MATRIX-001 | Get matrix data | Matrix grid |
| JD-PRES-MATRIX-002 | Get categories | Category list |
| JD-PRES-MATRIX-003 | Get proficiency levels | Level definitions |
| JD-PRES-MATRIX-004 | Get gap analysis | Gap calculations |

---

## 6. AJAX Handler Tests

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-AJAX-001 | jd_list request | JSON list |
| JD-AJAX-002 | jd_create request | JSON created ID |
| JD-AJAX-003 | jd_update request | JSON success |
| JD-AJAX-004 | jd_approve request | JSON success |
| JD-AJAX-005 | jd_reject request | JSON with reason |
| JD-AJAX-006 | comp_search request | JSON suggestions |
| JD-AJAX-007 | gap_analysis request | JSON gap data |
| JD-AJAX-008 | matrix_data request | JSON matrix |
| JD-AJAX-009 | Invalid request | 400 error |

---

## 7. Integration Tests

### 7.1 With ksf_JobDescriptions (Business Logic)

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-INT-BIZ-001 | Service injection | Service available |
| JD-INT-BIZ-002 | CRUD operations | Data persisted |

### 7.2 With ksf_Training

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-INT-TRN-001 | Get training requirements | Training list |
| JD-INT-TRN-002 | Recommend training for gap | Training suggested |

### 7.3 With ksf_Performance

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-INT-PRF-001 | Get competency assessments | Assessment data |
| JD-INT-PRF-002 | Update employee competencies | Competencies updated |

### 7.4 With FA Dimensions

| Test ID | Description | Expected Result |
|---------|-------------|-----------------|
| JD-INT-FA-001 | Department integration | Departments loaded |
| JD-INT-FA-002 | Dimension mapping | Dimension linked |

---

## 8. Test Data Fixtures

```php
$jobDescriptionData = [
    'id' => 1,
    'title' => 'Senior Software Engineer',
    'department_id' => 5,
    'status' => 'active',
    'description' => 'Leads technical development...',
    'responsibilities' => ['Code review', 'Architecture design', 'Mentoring'],
    'qualifications' => ['5+ years experience', 'BS in CS'],
    'hierarchy_level' => 4,
    'created_by' => 'hr_manager'
];

$competencyData = [
    'id' => 1,
    'name' => 'Python Programming',
    'category' => 'Technical',
    'description' => 'Proficiency in Python development',
    'proficiency_levels' => [
        1 => 'Basic syntax knowledge',
        2 => 'Can write simple scripts',
        3 => 'Can develop applications',
        4 => 'Expert in frameworks',
        5 => 'Framework contributor'
    ],
    'is_active' => true
];

$departmentData = [
    'id' => 5,
    'name' => 'Engineering',
    'dimension_code' => 'DEPT',
    'parent_id' => 1,
    'head_user_id' => 'dept_head_001'
];
```

---

## 9. Test Execution

```bash
# Run all tests
composer test

# Run unit tests
./vendor/bin/phpunit tests/Unit/ksf_FA_JobDescriptions

# Run FA integration tests
./vendor/bin/phpunit tests/Integration/FA

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/ tests/
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*