# Functional Requirements - ksf_JobDescriptions_UI

## Document Information
- **Module**: ksf_JobDescriptions_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 JobDescriptionListView

| Component | Type | Description |
|-----------|------|-------------|
| FilterBar | Filter | Department, status, date range filters |
| SearchBox | Input | Full-text search |
| JobDescriptionTable | Table | Paginated list with sorting |
| Pagination | Pagination | Page navigation |
| ExportButton | Button | PDF/CSV export |
| CreateButton | Button | New job description |

### 1.2 JobDescriptionForm

| Component | Type | Description |
|-----------|------|-------------|
| TitleInput | Input | Job title field |
| DepartmentSelect | Select | Department dropdown |
| DescriptionEditor | Textarea | Rich text description |
| CompetencySelector | MultiSelect | Skills/competencies picker |
| ResponsibilitiesList | List | Dynamic responsibilities |
| QualificationsList | List | Dynamic qualifications |
| TemplateSelect | Select | Base template picker |
| StatusSelect | Select | Draft/Active/Archived |
| SaveButton | Button | Submit form |
| CancelButton | Button | Discard changes |

### 1.3 CompetencyMatrixView

| Component | Type | Description |
|-----------|------|-------------|
| DepartmentFilter | Filter | Filter by department |
| CompetencyGrid | Grid | Skills vs levels matrix |
| CompetencyBadge | Badge | Level indicator (1-5) |
| CompareButton | Button | Compare with employee |

---

## 2. AJAX Endpoints

### 2.1 Job Description CRUD

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `jd_list` | GET | page, limit, filter | Paginated list |
| `jd_get` | GET | id | Single record |
| `jd_create` | POST | formData | Created record |
| `jd_update` | POST | id, formData | Updated record |
| `jd_delete` | POST | id | Success/error |

### 2.2 Search & Lookup

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `jd_search` | GET | q | Search results |
| `jd_competency_search` | GET | q | Competency list |
| `jd_template_list` | GET | - | Templates |

### 2.3 Export

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `jd_export_pdf` | GET | id | PDF file |
| `jd_export_csv` | GET | ids | CSV file |

---

## 3. Form Validation

### 3.1 Client-Side Validation

| Field | Rule | Message |
|-------|------|---------|
| title | Required, max 200 | Required |
| department_id | Required | Required |
| description | Required, min 100 chars | Too short |
| competencies | At least 1 | Required |

### 3.2 Server-Side Validation

| Field | Rule | Message |
|-------|------|---------|
| title | Sanitize, XSS check | Invalid input |
| department_id | FK validation | Invalid department |
| competencies | Array validation | Invalid competencies |

---

## 4. Page Requirements

### 4.1 Responsive Design

- Desktop: Full table view
- Tablet: Collapsible filters
- Mobile: Card-based list

### 4.2 Accessibility (WCAG 2.1 AA)

- Keyboard navigation
- ARIA labels
- Color contrast compliance
- Screen reader support

---

## 5. Integration Requirements

### 5.1 With ksf_JobDescriptions (Business Logic)

```php
// Service injection
$service = $container->get(JobDescriptionServiceInterface::class);
```

### 5.2 With ksf_Documents

- Attach supporting documents to job descriptions
- Store job description PDFs

### 5.3 With ksf_Training

- Link required training to job descriptions
- Show training status

### 5.4 With ksf_Performance

- Compare employee competencies vs required
- Gap analysis display

---

## 6. UI States

### 6.1 Loading States

- Skeleton loaders for lists
- Spinner for form submission
- Progress bar for exports

### 6.2 Empty States

- No results: Search suggestions
- No job descriptions: Create CTA

### 6.3 Error States

- Toast notifications for AJAX errors
- Inline field errors for validation
- Modal for critical errors

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
