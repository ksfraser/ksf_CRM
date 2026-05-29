# Functional Requirements - ksf_Recruitment_UI

## Document Information
- **Module**: ksf_Recruitment_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Job Opening Components

| Component | Type | Description |
|-----------|------|-------------|
| FilterBar | Filter | Department, status, location |
| SearchBox | Input | Full-text search |
| JobOpeningTable | Table | List with actions |
| CreateButton | Button | New opening |
| StatusBadge | Badge | Open/closed/filled |
| DepartmentSelect | Select | Department filter |

### 1.2 Candidate Components

| Component | Type | Description |
|-----------|------|-------------|
| CandidateTable | Table | Paginated candidate list |
| CandidateCard | Card | Compact view |
| CandidateProfile | Page | Full profile |
| ResumeViewer | Viewer | PDF resume display |
| SourceSelect | Select | Source filter |
| StageFilter | Filter | Pipeline stage filter |

### 1.3 Pipeline Components

| Component | Type | Description |
|-----------|------|-------------|
| PipelineBoard | Board | Kanban board |
| PipelineColumn | Column | Stage column |
| CandidateChip | Chip | Draggable candidate |
| MoveConfirm | Modal | Stage move confirmation |
| StageCounter | Counter | Candidates per stage |

### 1.4 Interview Components

| Component | Type | Description |
|-----------|------|-------------|
| InterviewCalendar | Calendar | Schedule view |
| InterviewForm | Form | Schedule new |
| FeedbackForm | Form | Submit feedback |
| AvailabilityGrid | Grid | Interviewer availability |

---

## 2. AJAX Endpoints

### 2.1 Job Openings

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `rec_jobs` | GET | filter | Job list |
| `rec_job_get` | GET | job_id | Job details |
| `rec_job_save` | POST | formData | Saved |
| `rec_job_archive` | POST | job_id | Archived |

### 2.2 Candidates

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `rec_candidates` | GET | filter | Candidate list |
| `rec_candidate_search` | GET | q | Search results |
| `rec_candidate_get` | GET | id | Profile |
| `rec_bulk_action` | POST | action, ids | Result |

### 2.3 Pipeline

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `rec_pipeline` | GET | job_id | Pipeline view |
| `rec_move` | POST | candidate_id, stage | Moved |
| `rec_stage_stats` | GET | job_id | Stats |

### 2.4 Interviews

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `rec_schedule_interview` | POST | data | Scheduled |
| `rec_feedback` | POST | interview_id, feedback | Saved |
| `rec_offers` | POST | candidate_id, offerData | Generated |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| title | Required, max 200 | Required |
| department_id | Required | Required |
| job_description_id | Required | Required |
| candidate_email | Valid email | Invalid email |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| job_description_id | FK validation | Invalid job |
| hiring_manager_id | FK validation | Invalid manager |
| stage | Valid stage | Invalid stage |

---

## 4. Integration Requirements

- ksf_JobDescriptions: Job requirements
- ksf_Calendar: Interview scheduling
- ksf_Documents: Resume storage

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
