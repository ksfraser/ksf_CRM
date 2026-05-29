# Architecture - ksf_Onboarding_UI

## Document Information
- **Module**: ksf_Onboarding_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_Onboarding_UI provides the FrontAccounting web interface layer for employee onboarding.

### 1.1 Namespace
```php
Ksfraser\FA\Onboarding\UI\
```

### 1.2 Layer Pattern
```
ksf_Onboarding_UI/
├── composer.json
├── ProjectDcs/
├── pages/                        → FA page handlers
├── js/                           → AJAX handlers
├── src/Ksfraser/FA/Onboarding/UI/
│   ├── Presenter/
│   │   ├── DashboardPresenter.php
│   │   ├── OnboardingWizardPresenter.php
│   │   └── TaskListPresenter.php
│   ├── Component/
│   │   ├── TaskCard.php
│   │   ├── ProgressRing.php
│   │   ├── Timeline.php
│   │   └── DocumentUploader.php
│   └── Handler/
│       └── OnboardingAjaxHandler.php
└── templates/
    └── onboarding/
```

---

## 2. Presenter Layer

### 2.1 DashboardPresenter

```php
class DashboardPresenter {
    public function getOnboardingSummary(string $hireId): array;
    public function getPendingTasks(string $hireId): array;
    public function getOverdueTasks(): array;
    public function getUpcomingDeadlines(): array;
}
```

### 2.2 OnboardingWizardPresenter

```php
class OnboardingWizardPresenter {
    public function getWizardSteps(): array;
    public function getStepData(string $hireId, int $step): array;
    public function saveStep(array $data): bool;
    public function completeOnboarding(string $hireId): bool;
}
```

### 2.3 TaskListPresenter

```php
class TaskListPresenter {
    public function getTasks(string $hireId): array;
    public function getTasksByCategory(string $hireId, string $category): array;
    public function assignTask(string $taskId, string $assigneeId): bool;
    public function updateProgress(string $taskId, int $progress): bool;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `TaskCard` | Task display with status |
| `ProgressRing` | Circular progress indicator |
| `Timeline` | Onboarding timeline view |
| `DocumentUploader` | Drag-drop document upload |
| `ChecklistItem` | Task checkbox with details |
| `DueDateBadge` | Due date with urgency color |
| `AssigneeSelect` | User assignment dropdown |

---

## 4. AJAX Handler Layer

### 4.1 jsAjax Handlers

| Action | Method | Description |
|--------|--------|-------------|
| `onb_dashboard` | handleDashboard | Dashboard data |
| `onb_tasks` | handleTasks | Task list |
| `onb_update_task` | handleUpdateTask | Update task status |
| `onb_upload_doc` | handleUploadDoc | Document upload |
| `onb_assign` | handleAssign | Assign task |
| `onb_progress` | handleProgress | Update progress |
| `onb_complete` | handleComplete | Mark step complete |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Onboarding Dashboard | `pages/onboarding_dashboard.php` | Overview |
| New Hire Wizard | `pages/onboarding_wizard.php` | Multi-step wizard |
| Task Checklist | `pages/onboarding_tasks.php` | Task list by category |
| Document Upload | `pages/onboarding_docs.php` | Document management |

---

## 6. Integration Points

### 6.1 With ksf_Recruitment
```php
// Get hire information
$recruitmentService = container()->get(RecruitmentServiceInterface::class);
```

### 6.2 With ksf_Training
```php
// Assign training tasks
$trainingService = container()->get(TrainingServiceInterface::class);
```

### 6.3 With ksf_Documents
```php
// Store onboarding documents
$documentService = container()->get(DocumentServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
