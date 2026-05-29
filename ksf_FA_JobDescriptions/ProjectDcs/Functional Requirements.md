# Functional Requirements - ksf_FA_JobDescriptions

## Document Information
- **Module**: ksf_FA_JobDescriptions
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. Overview

### 1.1 Purpose
ksf_FA_JobDescriptions provides job description management integrated with FrontAccounting's department and employee data.

### 1.2 Scope
- Job description CRUD
- Competency management
- FA department integration
- Template system
- Approval workflow

---

## 2. Core Entities

### 2.1 JobDescription

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| title | string | Yes | Job title |
| department_id | int | No | FK to FA dimension |
| status | string | Yes | draft/pending/active/archived |
| template_id | int | No | FK to template |
| description | text | No | Job overview |
| responsibilities | text | No | Key responsibilities |
| qualifications | text | No | Education/experience |
| hierarchy_level | int | No | Org chart level |
| reports_to_id | int | No | Parent job description |
| version | int | Yes | Version number |
| created_by | string | Yes | User who created |
| approved_by | string | No | Approver |
| approved_date | DateTime | No | Approval timestamp |
| inactive | bool | No | Soft delete |
| created_at | DateTime | Yes | Auto |
| updated_at | DateTime | Yes | Auto |

### 2.2 Competency

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| name | string | Yes | Competency name |
| category | string | No | Category (technical, soft) |
| description | text | No | Definition |
| proficiency_levels | text | No | JSON array of levels |
| is_active | bool | Yes | Active flag |
| created_at | DateTime | Yes | Auto |

### 2.3 JobDescriptionCompetency

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| job_description_id | int | Yes | FK to JobDescription |
| competency_id | int | Yes | FK to Competency |
| required_level | int | Yes | 1-5 |
| importance | string | Yes | required/preferred |
| notes | text | No | Additional context |

### 2.4 JobDescriptionTemplate

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | Yes | Primary key |
| name | string | Yes | Template name |
| category | string | No | Template category |
| default_responsibilities | text | No | Template content |
| default_qualifications | text | No | Template content |
| is_active | bool | Yes | Active flag |

---

## 3. Functional Requirements

### FR-JD-001: Job Description Management
**Requirement**: System shall allow CRUD operations for job descriptions.

**Features**:
- Create job description with title, description, responsibilities
- Edit job description details
- Archive job descriptions
- Link to FA department
- Set reporting hierarchy
- Version tracking

### FR-JD-002: Approval Workflow
**Requirement**: System shall support job description approval.

**Features**:
- Draft → Pending Approval workflow
- Approve/reject with comments
- Approval history
- Notification on status change
- Department head as approver

### FR-JD-003: Competency Management
**Requirement**: System shall manage competency library.

**Features**:
- CRUD for competencies
- Proficiency level definitions (1-5)
- Category organization
- Search competencies
- Import/export

### FR-JD-004: Job-Competency Assignment
**Requirement**: System shall link competencies to job descriptions.

**Features**:
- Assign required competencies
- Set required proficiency level
- Mark as required/preferred
- Bulk assignment from template
- Competency recommendations

### FR-JD-005: FA Department Integration
**Requirement**: System shall integrate with FA departments.

**Features**:
- Read departments from FA dimensions
- Department-based job listing
- Department head integration
- Hierarchy visualization

### FR-JD-006: Template System
**Requirement**: System shall support job description templates.

**Features**:
- Create/edit templates
- Template categories
- Apply template to new job
- Template versioning

### FR-JD-007: Gap Analysis
**Requirement**: System shall calculate competency gaps.

**Features**:
- Compare employee vs job required
- Gap calculation (required - actual)
- Gap visualization chart
- Training recommendations

### FR-JD-008: Search & Filter
**Requirement**: System shall provide search and filter capabilities.

**Features**:
- Full-text search (title, description)
- Filter by department
- Filter by status
- Filter by competency
- Sort by multiple fields

---

## 4. User Interactions

### 4.1 Job Description Creation Flow

1. User selects HR > Job Descriptions > New
2. Select template (optional)
3. Enter job title
4. Select department (from FA)
5. Set hierarchy level
6. Add description, responsibilities, qualifications
7. Add required competencies
8. Save as Draft
9. Submit for approval

### 4.2 Approval Flow

1. Department head receives notification
2. Review job description
3. Approve or reject with comments
4. If rejected → return to drafter
5. If approved → status = Active

### 4.3 Competency Gap Analysis

1. Select job description
2. Select employee
3. System fetches:
   - Required competencies (from job)
   - Employee competencies (from performance)
4. Calculate gaps
5. Display visualization
6. Generate training plan (ksf_Training)

---

## 5. FA Hook Integration

### 5.1 Security Areas

```php
SS_JOB_DESC = 116 << 8
SA_JOB_DESC_VIEW = SS_JOB_DESC | 1
SA_JOB_DESC_CREATE = SS_JOB_DESC | 2
SA_JOB_DESC_APPROVE = SS_JOB_DESC | 3
SA_JOB_DESC_ADMIN = SS_JOB_DESC | 4
```

### 5.2 Menu Items

| Menu | Title | Path | Permission |
|------|-------|------|------------|
| HRM | Job Descriptions | /modules/.../job_descriptions.php | SA_JOB_DESC_VIEW |
| HRM | Competency Matrix | /modules/.../competency_matrix.php | SA_JOB_DESC_VIEW |
| Setup | Job Description Setup | /modules/.../setup.php | SA_JOB_DESC_ADMIN |

---

## 6. Database Integration

### 6.1 Tables

| Table | Purpose |
|-------|---------|
| `{TB_PREF}job_description` | Job description records |
| `{TB_PREF}job_competency` | Competency library |
| `{TB_PREF}job_description_competency` | Job-competency links |
| `{TB_PREF}job_description_template` | Templates |
| `{TB_PREF}job_description_version` | Version history |

### 6.2 FA Table Usage

| FA Table | Purpose |
|----------|---------|
| `{TB_PREF}dimensions` | Department data |
| `{TB_PREF}dimensions_detail` | Department details |
| `{TB_PREF}employee` | Employee data |
| `{TB_PREF}users` | Approver assignment |

---

## 7. AJAX Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| jd_list | GET | List job descriptions |
| jd_get | GET | Single job description |
| jd_create | POST | Create job description |
| jd_update | POST | Update job description |
| jd_delete | POST | Archive job description |
| jd_approve | POST | Approve job description |
| jd_reject | POST | Reject with reason |
| comp_list | GET | List competencies |
| comp_search | GET | Search competencies |
| comp_levels | GET | Proficiency levels |
| gap_analysis | GET | Competency gap |
| matrix_data | GET | Competency matrix |

---

## 8. Composer Dependencies

| Package | Version | Purpose |
|---------|---------|---------|
| ksfraser/exceptions | ^1.2 | Exception hierarchy |
| ksfraser/traits | ^1.0 | Trait library |
| ksfraser/jobdescriptions | * | Business logic |

---

## 9. Exceptions

| Exception | Extends | Description |
|-----------|---------|-------------|
| JobDescriptionException | RuntimeException | Base exception |
| JobDescriptionNotFoundException | JobDescriptionException | Not found |
| JobDescriptionValidationException | JobDescriptionException | Validation errors |
| CompetencyNotFoundException | JobDescriptionException | Competency not found |
| ApprovalDeniedException | JobDescriptionException | Not authorized |

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*