# Functional Requirements - ksf_Training_UI

## Document Information
- **Module**: ksf_Training_UI
- **Version**: 1.0.0
- **Date**: 2026-05-11

---

## 1. UI Components

### 1.1 Catalog Components

| Component | Type | Description |
|-----------|------|-------------|
| CourseGrid | Grid | Course cards |
| CategoryFilter | Filter | Category sidebar |
| SearchBox | Input | Search courses |
| FeaturedCourse | Highlight | Featured course |
| EnrollButton | Button | Quick enroll |

### 1.2 Course Form Components

| Component | Type | Description |
|-----------|------|-------------|
| CourseTitle | Input | Course name |
| DescriptionEditor | Textarea | Rich text |
| CategorySelect | Select | Course category |
| DurationInput | Input | Hours/days |
| CompetencyTag | Tags | Linked competencies |
| MaterialUploader | Upload | Course materials |
| SessionBuilder | Builder | Add sessions |
| PublishButton | Button | Publish course |

### 1.3 Attendance Components

| Component | Type | Description |
|-----------|------|-------------|
| AttendanceTable | Table | Employee list |
| StatusCheckbox | Checkbox | Present/absent |
| DatePicker | Date | Session date |
| CompletionBadge | Badge | Completed status |
| CertificateButton | Button | Generate cert |

---

## 2. AJAX Endpoints

### 2.1 Courses

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `trn_courses` | GET | filter | Course list |
| `trn_course_get` | GET | course_id | Course |
| `trn_course_save` | POST | formData | Saved |
| `trn_course_delete` | POST | course_id | Deleted |

### 2.2 Enrollments

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `trn_enroll` | POST | employee_id, session_id | Enrolled |
| `trn_cancel` | POST | enrollment_id | Cancelled |
| `trn_enrollments` | GET | filter | List |
| `trn_completion` | GET | employee_id | Stats |

### 2.3 Sessions

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `trn_sessions` | GET | course_id | Session list |
| `trn_session_save` | POST | sessionData | Saved |
| `trn_attendance` | POST | session_id, data | Marked |

### 2.4 Feedback & Certificates

| Endpoint | Method | Parameters | Response |
|----------|--------|------------|----------|
| `trn_feedback` | POST | enrollment_id, feedback | Saved |
| `trn_certificate` | POST | enrollment_id | PDF |

---

## 3. Form Validation

### 3.1 Client-Side

| Field | Rule | Message |
|-------|------|---------|
| course_title | Required, max 200 | Required |
| duration | Positive number | Invalid |
| session_date | Future date | Invalid |
| employee_id | Required | Required |

### 3.2 Server-Side

| Field | Rule | Message |
|-------|------|---------|
| course_id | FK validation | Invalid course |
| instructor_id | FK validation | Invalid instructor |
| capacity | Positive int | Invalid capacity |

---

## 4. Integration Requirements

- ksf_JobDescriptions: Training requirements
- ksf_Performance: Competency assessment
- ksf_Documents: Materials and certificates

---

*Document Version: 1.0.0*
*Last Updated: 2026-05-11*
