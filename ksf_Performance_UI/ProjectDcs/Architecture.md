# Architecture - ksf_Performance_UI

## Document Information
- **Module**: ksf_Performance_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_Performance_UI provides the FrontAccounting web interface layer for performance management.

### 1.1 Namespace
```php
Ksfraser\FA\Performance\UI\
```

### 1.2 Layer Pattern
```
ksf_Performance_UI/
├── composer.json
├── ProjectDcs/
├── pages/
├── js/
├── src/Ksfraser/FA/Performance/UI/
│   ├── Presenter/
│   │   ├── ReviewListPresenter.php
│   │   ├── ReviewFormPresenter.php
│   │   ├── GoalTrackerPresenter.php
│   │   └── CalibrationPresenter.php
│   ├── Component/
│   │   ├── ReviewCard.php
│   │   ├── GoalProgress.php
│   │   ├── RatingScale.php
│   │   ├── CompetencyMatrix.php
│   │   └── FeedbackForm.php
│   └── Handler/
│       └── PerformanceAjaxHandler.php
└── templates/
    └── performance/
```

---

## 2. Presenter Layer

### 2.1 ReviewListPresenter

```php
class ReviewListPresenter {
    public function getReviews(array $filters): array;
    public function getReviewCycles(): array;
    public function getPendingReviews(): array;
    public function exportReview(string $id): string;
}
```

### 2.2 ReviewFormPresenter

```php
class ReviewFormPresenter {
    public function getReviewForm(string $id = null): array;
    public function saveReview(array $data): PerformanceReview;
    public function submitForApproval(string $id): bool;
    public function getCompetencies(string $jobDescId): array;
}
```

### 2.3 GoalTrackerPresenter

```php
class GoalTrackerPresenter {
    public function getGoals(string $employeeId): array;
    public function updateGoalProgress(string $goalId, int $progress): bool;
    public function addMilestone(string $goalId, array $data): Milestone;
    public function getGoalAlignment(): array;
}
```

### 2.4 CalibrationPresenter

``` java
class CalibrationPresenter {
    public function getCalibrationMatrix(string $cycleId): array;
    public function moveRating(string $employeeId, string $band): bool;
    public function getDiscussionNotes(string $employeeId): array;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `ReviewCard` | Review summary card |
| `GoalProgress` | Goal progress bar |
| `RatingScale` | 1-5 rating selector |
| `CompetencyMatrix` | Skills assessment grid |
| `FeedbackForm` | 360 feedback form |
| `ReviewStatusBadge` | Status indicator |
| `CalibrationDot` | Drag-drop rating dot |

---

## 4. AJAX Handler Layer

| Action | Method | Description |
|--------|--------|-------------|
| `perf_reviews` | handleReviews | List reviews |
| `perf_review_get` | handleGetReview | Get single review |
| `perf_review_save` | handleSaveReview | Save review |
| `perf_goals` | handleGoals | Goal operations |
| `perf_goal_update` | handleGoalUpdate | Update goal |
| `perf_rating` | handleRating | Submit rating |
| `perf_calibrate` | handleCalibrate | Calibration |
| `perf_export` | handleExport | Export review |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Review List | `pages/performance_reviews.php` | List view |
| Review Form | `pages/performance_review_edit.php` | Review editor |
| Goal Tracker | `pages/goal_tracker.php` | Goals dashboard |
| Calibration | `pages/calibration.php` | Calibration view |
| Reports | `pages/performance_reports.php` | Reports |

---

## 6. Integration Points

### 6.1 With ksf_JobDescriptions
```php
// Get competencies for assessment
$jobDescService = container()->get(JobDescriptionServiceInterface::class);
```

### 6.2 With ksf_Training
```php
// Link training to performance
$trainingService = container()->get(TrainingServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
