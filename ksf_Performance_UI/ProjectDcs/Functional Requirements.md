# Functional Requirements - ksf_Performance_UI

## Document Information
- **Module**: ksf_Performance_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Review List Components

| Component | Type | Description |
|-----------|------|-------------|
| FilterBar | Filter | Cycle, status, department |
| ReviewTable | Table | Paginated list |
| StatusBadge | Badge | Draft/submitted/approved |
| ActionMenu | Menu | Edit, view, export |

### 1.2 Review Form Components

| Component | Type | Description |
|-----------|------|-------------|
| EmployeeInfo | Section | Employee details |
| ReviewPeriod | Section | Review dates |
| CompetencyRatings | Grid | Skills 1-5 |
| GoalAssessment | List | Goal achievement |
| OverallRating | Scale | Final rating |
| Comments | Textarea | Manager comments |
| FeedbackForm | Form | Employee self-review |

### 1.3 Goal Tracker Components

| Component | Type | Description |
|-----------|------|-------------|
| GoalCard | Card | Goal with progress |
| ProgressBar | Bar | Percentage complete |
| MilestoneList | List | Milestones |
| AddGoalButton | Button | New goal |
| AlignmentBadge | Badge | Goal alignment status |

---

## 2. AJAX Endpoints

### 2.1 Reviews

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `perf_reviews` | GET | cycle_id, status | List |
| `perf_review_get` | GET | review_id | Review |
| `perf_review_save` | POST | formData | Saved |
| `perf_review_submit` | POST | review_id | Submitted |

### 2.2 Goals

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `perf_goals` | GET | employee_id | Goals |
| `perf_goal_create` | POST | goalData | Created |
| `perf_goal_update` | POST | goal_id, progress | Updated |
| `perf_goal_delete` | POST | goal_id | Deleted |

### 2.3 Calibration

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `perf_calibration` | GET | cycle_id | Matrix |
| `perf_move_rating` | POST | employee_id, band | Moved |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| competency_rating | 1-5 required | Select rating |
| overall_rating | 1-5 required | Select rating |
| comments | Max 5000 chars | Too long |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| employee_id | FK validation | Invalid employee |
| review_cycle_id | FK validation | Invalid cycle |
| ratings | Array validation | Invalid data |

---

## 4. Integration Requirements

- ksf_JobDescriptions: Competency definitions
- ksf_Training: Training completion

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
