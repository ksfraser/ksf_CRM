# Architecture - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_FA_JobDescriptions provides FrontAccounting integration for HR job description management, leveraging FA's department and dimension data.

### 1.1 Namespace
```php
Ksfraser\FA\JobDescriptions\
```

### 1.2 Layer Pattern
```
ksf_FA_JobDescriptions/
├── composer.json
├── AGENTS.md
├── hooks.php                      → FA hooks (extension)
├── ProjectDcs/                    ← THIS DOCUMENTATION
├── pages/
│   ├── job_descriptions.php       → Job description list
│   ├── job_description_edit.php    → Create/edit form
│   ├── competency_matrix.php      → Skills matrix view
│   ├── job_templates.php          → Template management
│   └── competency_admin.php       → Competency library
├── Integration/
│   ├── DepartmentAdapter.php       → FA department integration
│   ├── DimensionAdapter.php       → FA dimension integration
│   └── EmployeeAdapter.php        → Employee competency data
└── src/
    └── Ksfraser/FA/JobDescriptions/
        ├── Presenter/
        │   ├── JobDescriptionListPresenter.php
        │   ├── JobDescriptionFormPresenter.php
        │   └── CompetencyMatrixPresenter.php
        ├── Component/
        │   ├── JobDescriptionCard.php
        │   ├── CompetencyBadge.php
        │   ├── CompetencySelector.php
        │   ├── TemplatePicker.php
        │   └── GapAnalysisChart.php
        └── Handler/
            └── JobDescriptionAjaxHandler.php
```

---

## 2. FA Hook Integration

### 2.1 hooks.php Structure

```php
class hooks_ksf_fa_jobdescriptions extends hooks {
    var $module_name = 'ksf_FA_JobDescriptions';

    function install_access() {
        $security_sections['SS_JOB_DESC'] = _("Job Description Management");
        $security_areas['SA_JOB_DESC_VIEW'] = array(SS_JOB_DESC | 1, _("View Job Descriptions"));
        $security_areas['SA_JOB_DESC_CREATE'] = array(SS_JOB_DESC | 2, _("Manage Job Descriptions"));
        $security_areas['SA_JOB_DESC_APPROVE'] = array(SS_JOB_DESC | 3, _("Approve Job Descriptions"));
        return array($security_areas, $security_sections);
    }

    function install_options($app) {
        switch($app->id) {
            case 'hrm':
                $app->add_lapp_function(0, _("Job Descriptions"),
                    $path_to_root."/modules/ksf_FA_JobDescriptions/job_descriptions.php",
                    'SA_JOB_DESC_VIEW', MENU_MAIN);
                $app->add_lapp_function(1, _("Competency Matrix"),
                    $path_to_root."/modules/ksf_FA_JobDescriptions/competency_matrix.php",
                    'SA_JOB_DESC_VIEW', MENU_ENTRY);
                $app->add_rapp_function(2, _("Job Description Setup"),
                    $path_to_root."/modules/ksf_FA_JobDescriptions/setup.php",
                    'SA_JOB_DESC_ADMIN', MENU_MAINTENANCE);
                break;
        }
    }

    function activate_extension($company, $check_only=true) {
        $updates = array('sql/update.sql' => array($this->module_name));
        return $this->update_databases($company, $updates, $check_only);
    }
}
```

---

## 3. Database Adapters

### 3.1 DepartmentAdapter

```php
namespace Ksfraser\FA\JobDescriptions\Integration;

class DepartmentAdapter {
    public function getDepartments(): array;
    public function getDepartment(string $id): ?array;
    public function getDepartmentHierarchy(): array;
    public function getDepartmentHead(string $deptId): ?string;
    public function getJobsByDepartment(string $deptId): array;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getDepartments | dimensions | Department list |
| getDepartmentHierarchy | dimensions | Parent-child tree |
| getDepartmentHead | users | Manager assignment |
| getJobsByDepartment | job_descriptions | Department jobs |

### 3.2 EmployeeAdapter

```php
class EmployeeAdapter {
    public function getEmployeesByDepartment(string $deptId): array;
    public function getEmployeeCompetencies(string $empId): array;
    public function getEmployeeCompetencyGap(string $empId, string $jobDescId): array;
    public function getCompetencyAssessments(string $empId): array;
}
```

| Method | FA Table | Purpose |
|--------|----------|---------|
| getEmployeesByDepartment | employee_active | Staff list |
| getEmployeeCompetencies | fa_performance_competencies | Current levels |
| getCompetencyAssessments | fa_performance_reviews | Assessment history |

---

## 4. Entity Adaptation

### 4.1 JobDescription (FA Adapted)

```php
namespace Ksfraser\FA\JobDescriptions\Entity;

class JobDescription {
    private string $id;
    private string $title;
    private ?string $departmentId;     // FA dimension
    private JobDescriptionStatus $status;
    private ?string $templateId;
    private string $description;
    private array $responsibilities;
    private array $qualifications;
    private array $competencies;         // Required competencies
    private ?int $hierarchyLevel;
    private ?string $reportsToId;       // Parent job

    // FA integration methods
    public function getDepartment(): ?array;
    public function getDepartmentHead(): ?string;
    public function getLinkedEmployees(): array;
    public function getCompetencyGapSummary(string $empId): array;
}
```

### 4.2 Competency (FA Adapted)

```php
class Competency {
    private string $id;
    private string $name;
    private string $category;
    private string $description;
    private array $proficiencyLevels;   // 1-5 with descriptions
    private bool $isActive;

    public function getProficiencyDescription(int $level): string;
    public function getLevelForEmployee(string $empId): ?int;
}
```

### 4.3 JobDescriptionCompetency

```php
class JobDescriptionCompetency {
    private string $id;
    private string $jobDescriptionId;
    private string $competencyId;
    private int $requiredLevel;         // 1-5
    private string $importance;         // required/preferred
    private string $notes;
}
```

---

## 5. State Machines

### 5.1 Job Description Status

```
Draft ──> Pending Approval ──> Active ──> Archived
    │              │               │
    └── Rejected ─┴───────────────┘
```

### 5.2 Competency Proficiency Levels

```
Level 1: Foundation     - Basic awareness
Level 2: Developing    - Working knowledge
Level 3: Proficient    - Applied regularly
Level 4: Advanced      - Expert level
Level 5: Master         - Thought leader
```

---

## 6. Presenter Layer

### 6.1 JobDescriptionListPresenter

```php
class JobDescriptionListPresenter {
    public function getJobDescriptions(array $filters): array;
    public function getDepartment(): array;
    public function getStatusOptions(): array;
    public function searchJobDescriptions(string $query): array;
    public function getJobDescriptionStats(): array;
}
```

### 6.2 JobDescriptionFormPresenter

```php
class JobDescriptionFormPresenter {
    public function getFormData(string $id = null): array;
    public function getDepartments(): array;
    public function getCompetencies(): array;
    public function getTemplates(): array;
    public function getParentJobs(string $deptId): array;
    public function saveJobDescription(array $data): JobDescription;
    public function validateJobDescription(array $data): ValidationResult;
}
```

### 6.3 CompetencyMatrixPresenter

```php
class CompetencyMatrixPresenter {
    public function getMatrix(string $departmentId = null): array;
    public function getCompetencyCategories(): array;
    public function getProficiencyLevels(): array;
    public function getJobCompetencies(string $jobId): array;
    public function getCompetencyGap(string $jobId, string $employeeId): array;
}
```

---

## 7. AJAX Handler

### 7.1 Handler Actions

| Action | Method | Description |
|--------|--------|-------------|
| jd_list | handleList | Paginated list with filters |
| jd_create | handleCreate | Create new job description |
| jd_update | handleUpdate | Update existing |
| jd_delete | handleDelete | Archive (soft delete) |
| jd_approve | handleApprove | Approve pending |
| jd_reject | handleReject | Reject with reason |
| jd_search | handleSearch | Full-text search |
| comp_search | handleCompetencySearch | Autocomplete |
| comp_levels | handleProficiencyLevels | Level definitions |
| gap_analysis | handleGapAnalysis | Competency gap |
| matrix_data | handleMatrixData | Matrix grid data |

---

## 8. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Job Description List | `pages/job_descriptions.php` | Main list |
| Job Description Edit | `pages/job_description_edit.php` | Create/edit |
| Competency Matrix | `pages/competency_matrix.php` | Skills matrix |
| Job Templates | `pages/job_templates.php` | Template management |
| Competency Admin | `pages/competency_admin.php` | Competency library |

---

## 9. Integration Points

### 9.1 With Business Logic
```php
$service = container()->get(JobDescriptionServiceInterface::class);
```

### 9.2 With ksf_Training
```php
// Required training per job description
$trainingService = container()->get(TrainingServiceInterface::class);
```

### 9.3 With ksf_Performance
```php
// Competency assessment integration
$performanceService = container()->get(PerformanceServiceInterface::class);
```

### 9.4 With ksf_Recruitment
```php
// Position template for job descriptions
$recruitmentService = container()->get(RecruitmentServiceInterface::class);
```

---

## 10. Error Handling

| Error Type | FA Handler Response |
|------------|---------------------|
| Validation Error | inline_errors() |
| Not Found | display_error() |
| Permission Denied | display_access_denied() |
| Approval Required | access_denied() |
| Database Error | db_error() |

---

## 11. Database Schema

```sql
-- Job descriptions
CREATE TABLE IF NOT EXISTS `{TB_PREF}job_description` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NOT NULL,
    `department_id` INT(11) DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'draft',
    `template_id` INT(11) DEFAULT NULL,
    `description` TEXT,
    `responsibilities` TEXT,
    `qualifications` TEXT,
    `hierarchy_level` INT(11) DEFAULT NULL,
    `reports_to_id` INT(11) DEFAULT NULL,
    `version` INT(11) DEFAULT 1,
    `created_by` VARCHAR(100) DEFAULT NULL,
    `approved_by` VARCHAR(100) DEFAULT NULL,
    `approved_date` DATETIME DEFAULT NULL,
    `inactive` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_department` (`department_id`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Competencies
CREATE TABLE IF NOT EXISTS `{TB_PREF}job_competency` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) DEFAULT NULL,
    `description` TEXT,
    `proficiency_levels` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_category` (`category`),
    KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Job description competencies
CREATE TABLE IF NOT EXISTS `{TB_PREF}job_description_competency` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `job_description_id` INT(11) NOT NULL,
    `competency_id` INT(11) NOT NULL,
    `required_level` TINYINT(1) DEFAULT 3,
    `importance` VARCHAR(20) DEFAULT 'required',
    `notes` TEXT,
    PRIMARY KEY (`id`),
    KEY `idx_job_description` (`job_description_id`),
    KEY `idx_competency` (`competency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Job description templates
CREATE TABLE IF NOT EXISTS `{TB_PREF}job_description_template` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) DEFAULT NULL,
    `default_responsibilities` TEXT,
    `default_qualifications` TEXT,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*