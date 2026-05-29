# Architecture - ksf_JobDescriptions_UI

## Document Information
- **Module**: ksf_JobDescriptions_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_JobDescriptions_UI provides the FrontAccounting web interface layer for job description management.

### 1.1 Namespace
```php
Ksfraser\FA\JobDescriptions\UI\
```

### 1.2 Layer Pattern
```
ksf_JobDescriptions_UI/
├── composer.json
├── ProjectDcs/                   ← THIS DOCUMENTATION
├── pages/                        → FA page handlers
├── js/                           → AJAX handlers (jsAjax)
├── src/Ksfraser/FA/JobDescriptions/UI/
│   ├── Presenter/                → MVVM presenters
│   ├── Component/               → Reusable UI components
│   ├── ViewModel/               → Data transformation
│   └── Handler/                  → AJAX request handlers
└── templates/                    → TWIG templates
```

---

## 2. Presenter Layer

### 2.1 JobDescriptionListPresenter

```php
class JobDescriptionListPresenter {
    public function getJobDescriptions(array $filters): array;
    public function getDepartments(): array;
    public function searchJobDescriptions(string $query): array;
    public function exportToPdf(array $ids): string;
}
```

### 2.2 JobDescriptionFormPresenter

```php
class JobDescriptionFormPresenter {
    public function getFormData(string $id = null): array;
    public function saveJobDescription(array $data): JobDescription;
    public function validateForm(array $data): ValidationResult;
    public function getCompetencies(): array;
    public function getTemplates(): array;
}
```

### 2.3 CompetencyMatrixPresenter

```php
class CompetencyMatrixPresenter {
    public function getMatrix(string $departmentId): array;
    public function compareCompetencies(string $jobDescId, string $employeeId): array;
    public function getCompetencyLevels(): array;
}
```

---

## 3. Component Layer

### 3.1 Components

| Component | Description |
|-----------|-------------|
| `JobDescriptionCard` | Card display for list items |
| `CompetencyBadge` | Skill level indicator |
| `CompetencySelector` | Autocomplete for competencies |
| `TemplatePicker` | Template selection dialog |
| `VersionHistory` | Change history viewer |
| `StatusIndicator` | Approval status display |
| `ExportButton` | PDF/CSV export trigger |

### 3.2 Component Structure

```
Component/
├── JobDescriptionCard.php
├── CompetencyBadge.php
├── CompetencySelector.php
├── TemplatePicker.php
├── VersionHistory.php
├── StatusIndicator.php
└── ExportButton.php
```

---

## 4. AJAX Handler Layer

### 4.1 jsAjax Handlers

```php
namespace Ksfraser\FA\JobDescriptions\UI\Handler;

class JobDescriptionAjaxHandler {
    public function handleList(Request $request): JsonResponse;
    public function handleCreate(Request $request): JsonResponse;
    public function handleUpdate(Request $request): JsonResponse;
    public function handleDelete(Request $request): JsonResponse;
    public function handleSearch(Request $request): JsonResponse;
    public function handleCompetencySearch(Request $request): JsonResponse;
    public function handleExport(Request $request): JsonResponse;
    public function handleVersionHistory(Request $request): JsonResponse;
}
```

### 4.2 Handler Actions

| Action | Method | Description |
|--------|--------|-------------|
| `jd_list` | handleList | Paginated list with filters |
| `jd_create` | handleCreate | Create new job description |
| `jd_update` | handleUpdate | Update existing |
| `jd_delete` | handleDelete | Soft delete |
| `jd_search` | handleSearch | Full-text search |
| `jd_competency_search` | handleCompetencySearch | Autocomplete |
| `jd_export` | handleExport | PDF generation |
| `jd_versions` | handleVersionHistory | Version list |

---

## 5. Page Handlers

### 5.1 Page Files

| Page | File | Purpose |
|------|------|---------|
| Job Descriptions | `pages/job_descriptions.php` | Main list view |
| Job Description Edit | `pages/job_description_edit.php` | Create/edit form |
| Competency Matrix | `pages/competency_matrix.php` | Skills matrix |
| Templates | `pages/job_templates.php` | Template management |

### 5.2 Page Structure Pattern

```php
// pages/job_descriptions.php
function render_job_descriptions_page(array $app) {
    $presenter = container()->get(JobDescriptionListPresenter::class);
    $data = $presenter->getJobDescriptions($app['filter']);
    return render_template('job_descriptions/list.twig', $data);
}
```

---

## 6. Template Structure

```
templates/
├── job_descriptions/
│   ├── list.twig
│   ├── list_item.twig
│   ├── form.twig
│   ├── detail.twig
│   ├── competency_matrix.twig
│   └── templates/
│       └── template_picker.twig
```

---

## 7. Integration Points

### 7.1 With Business Logic
```php
$service = container()->get(JobDescriptionServiceInterface::class);
$presenter->setService($service);
```

### 7.2 With ksf_Training
```php
// Link training requirements to job descriptions
$trainingService = container()->get(TrainingServiceInterface::class);
```

### 7.3 With ksf_Performance
```php
// Competency assessment integration
$performanceService = container()->get(PerformanceServiceInterface::class);
```

---

## 8. Error Handling

| Error Type | UI Response |
|------------|--------------|
| Validation Error | Inline field errors |
| Not Found | 404 page with message |
| Permission Denied | Access denied dialog |
| Service Error | Toast notification |

---

## 9. File Structure

```
ksf_JobDescriptions_UI/
├── composer.json
├── AGENTS.md
├── ProjectDcs/
│   ├── Business Requirements.md  ← THIS FILE
│   ├── Architecture.md
│   ├── Functional Requirements.md
│   ├── Use Case.md
│   ├── Test Plan.md
│   ├── UAT Plan.md
│   └── RTM.md
├── pages/
│   ├── job_descriptions.php
│   ├── job_description_edit.php
│   ├── competency_matrix.php
│   └── job_templates.php
├── js/
│   ├── job_descriptions.js
│   └── competency_selector.js
├── src/Ksfraser/FA/JobDescriptions/UI/
│   ├── Presenter/
│   │   ├── JobDescriptionListPresenter.php
│   │   ├── JobDescriptionFormPresenter.php
│   │   └── CompetencyMatrixPresenter.php
│   ├── Component/
│   │   └── [UI Components]
│   └── Handler/
│       └── JobDescriptionAjaxHandler.php
└── templates/
    └── job_descriptions/
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
