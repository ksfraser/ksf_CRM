# Functional Requirements - ksf_Onboarding_UI

## Document Information
- **Module**: ksf_Onboarding_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Dashboard Components

| Component | Type | Description |
|-----------|------|-------------|
| SummaryCards | Cards | Active, pending, completed counts |
| ProgressRing | Ring | Overall completion percentage |
| Timeline | Timeline | Visual timeline |
| TaskList | List | Pending tasks with due dates |
| QuickActions | Buttons | Common actions |

### 1.2 Wizard Components

| Component | Type | Description |
|-----------|------|-------------|
| StepIndicator | Steps | Multi-step progress |
| StepContent | Form | Current step form fields |
| NavigationButtons | Buttons | Previous/Next/Complete |
| DocumentUploader | Upload | Drag-drop file upload |
| TaskChecklist | Checkboxes | Onboarding tasks |

### 1.3 Task List Components

| Component | Type | Description |
|-----------|------|-------------|
| FilterBar | Filter | Category, status, assignee |
| TaskCard | Card | Task with assignee, due date |
| StatusBadge | Badge | Pending/in-progress/complete |
| AssigneeDropdown | Select | User assignment |
| DueDatePicker | Date | Set due dates |

---

## 2. AJAX Endpoints

### 2.1 Dashboard

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `onb_dashboard` | GET | hire_id | Dashboard data |
| `onb_summary` | GET | - | Overall stats |

### 2.2 Tasks

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `onb_tasks` | GET | hire_id, category | Task list |
| `onb_update_task` | POST | task_id, status | Updated task |
| `onb_assign_task` | POST | task_id, assignee_id | Success/error |
| `onb_set_due` | POST | task_id, due_date | Success/error |

### 2.3 Documents

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `onb_upload_doc` | POST | hire_id, file | Upload result |
| `onb_list_docs` | GET | hire_id | Document list |
| `onb_delete_doc` | POST | doc_id | Success/error |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| task_status | Required | Required |
| assignee_id | Valid user | Invalid user |
| due_date | Valid date | Invalid date |
| document | File type/size | Invalid file |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| hire_id | FK validation | Invalid hire |
| task_id | FK validation | Invalid task |
| document | Malware scan | Security check failed |

---

## 4. Integration Requirements

- ksf_Recruitment: Hire data
- ksf_Training: Training assignments
- ksf_Documents: Document storage
- ksf_JobDescriptions: Role info

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
