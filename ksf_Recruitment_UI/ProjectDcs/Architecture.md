# Architecture - ksf_Recruitment_UI

## Document Information
- **Module**: ksf_Recruitment_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11
- **Status**: Draft

---

## 1. Module Overview

ksf_Recruitment_UI provides the FrontAccounting web interface layer for recruitment management.

### 1.1 Namespace
```php
Ksfraser\FA\Recruitment\UI\
```

### 1.2 Layer Pattern
```
ksf_Recruitment_UI/
├── composer.json
├── ProjectDcs/
├── pages/
├── js/
├── src/Ksfraser/FA/Recruitment/UI/
│   ├── Presenter/
│   │   ├── JobOpeningListPresenter.php
│   │   ├── JobOpeningFormPresenter.php
│   │   ├── CandidateListPresenter.php
│   │   ├── CandidateProfilePresenter.php
│   │   ├── PipelinePresenter.php
│   │   └── HiringDashboardPresenter.php
│   ├── Component/
│   │   ├── JobOpeningCard.php
│   │   ├── CandidateCard.php
│   │   ├── PipelineColumn.php
│   │   ├── InterviewScheduler.php
│   │   ├── OfferGenerator.php
│   │   └── HiringMetrics.php
│   └── Handler/
│       └── RecruitmentAjaxHandler.php
└── templates/
    └── recruitment/
```

---

## 2. Presenter Layer

### 2.1 JobOpeningListPresenter

```php
class JobOpeningListPresenter {
    public function getJobOpenings(array $filters): array;
    public function getDepartments(): array;
    public function getOpeningsCount(): array;
    public function archiveOpening(string $id): bool;
}
```

### 2.2 CandidateListPresenter

```php
class CandidateListPresenter {
    public function getCandidates(array $filters): array;
    public function searchCandidates(string $query): array;
    public function getCandidatesBySource(string $source): array;
    public function bulkAction(string $action, array $ids): bool;
}
```

### 2.3 PipelinePresenter

```php
class PipelinePresenter {
    public function getPipelineView(string $jobOpeningId): array;
    public function moveCandidate(string $candidateId, string $stage): bool;
    public function getStageStats(string $jobOpeningId): array;
    public function getCandidatesInStage(string $jobOpeningId, string $stage): array;
}
```

### 2.4 CandidateProfilePresenter

```php
class CandidateProfilePresenter {
    public function getProfile(string $candidateId): array;
    public function getInterviews(string $candidateId): array;
    public function getFeedback(string $candidateId): array;
    public function getDocuments(string $candidateId): array;
}
```

---

## 3. Component Layer

| Component | Description |
|-----------|-------------|
| `JobOpeningCard` | Job summary card |
| `CandidateCard` | Candidate info card |
| `PipelineColumn` | Kanban column |
| `InterviewScheduler` | Calendar-based scheduling |
| `OfferGenerator` | Offer letter generator |
| `HiringMetrics` | KPI dashboard widgets |
| `StageBadge` | Pipeline stage indicator |
| `SourceTag` | Candidate source tag |

---

## 4. AJAX Handler Layer

| Action | Method | Description |
|--------|--------|-------------|
| `rec_jobs` | handleJobs | Job openings list |
| `rec_job_get` | handleJobGet | Single job |
| `rec_job_save` | handleJobSave | Create/update job |
| `rec_candidates` | handleCandidates | Candidate list |
| `rec_candidate_search` | handleCandidateSearch | Search |
| `rec_pipeline` | handlePipeline | Pipeline view |
| `rec_move` | handleMove | Move candidate stage |
| `rec_interview` | handleInterview | Schedule interview |
| `rec_feedback` | handleFeedback | Submit feedback |
| `rec_offer` | handleOffer | Generate offer |

---

## 5. Page Handlers

| Page | File | Purpose |
|------|------|---------|
| Job Openings | `pages/job_openings.php` | List |
| Job Opening Edit | `pages/job_opening_edit.php` | Create/edit |
| Candidates | `pages/candidates.php` | Database |
| Candidate Profile | `pages/candidate_profile.php` | View |
| Pipeline | `pages/recruitment_pipeline.php` | Kanban |
| Interviews | `pages/interviews.php` | Schedule |
| Hiring Dashboard | `pages/hiring_dashboard.php` | KPIs |

---

## 6. Integration Points

### 6.1 With ksf_JobDescriptions
```php
// Get job requirements
$jobDescService = container()->get(JobDescriptionServiceInterface::class);
```

### 6.2 With ksf_Calendar
```php
// Schedule interviews
$calendarService = container()->get(CalendarServiceInterface::class);
```

### 6.3 With ksf_Documents
```php
// Store resumes
$documentService = container()->get(DocumentServiceInterface::class);
```

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
